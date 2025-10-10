<?php

namespace App\Http\Controllers;

use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\CompteFinancier;
use App\Models\Appartement;
use App\Models\Locataire;
use Illuminate\Http\Request;
use PDF;
use Excel;

class RapportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Données simulées pour les statistiques
        $stats = [
            'total_immeubles' => 15,
            'immeubles_actifs' => 12,
            'total_appartements' => 120,
            'appartements_disponibles' => 25,
            'appartements_occupes' => 95,
            'total_locataires' => 95,
            'locataires_actifs' => 90,
            'revenus_mensuels' => 45000000
        ];

        // Données simulées pour les retards
        $retards = collect([
            (object) [
                'locataire' => (object) ['nom' => 'KASONGO Marie'],
                'appartement' => (object) ['numero' => 'A12'],
                'montant' => 350000,
                'jours_retard' => 15
            ],
            (object) [
                'locataire' => (object) ['nom' => 'MPIANA Jean'],
                'appartement' => (object) ['numero' => 'B05'],
                'montant' => 280000,
                'jours_retard' => 8
            ]
        ]);

        // Données simulées pour le top des immeubles
        $top_immeubles = collect([
            (object) [
                'nom' => 'Immeuble Central',
                'appartements_count' => 24,
                'revenus' => 8400000
            ],
            (object) [
                'nom' => 'Résidence Moderne',
                'appartements_count' => 18,
                'revenus' => 6300000
            ]
        ]);

        // Données pour les graphiques
        $chartData = [
            'revenus' => [
                'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                'data' => [38000000, 42000000, 39000000, 45000000, 47000000, 44000000, 46000000, 48000000, 45000000, 45000000, 43000000, 45000000]
            ]
        ];

        return view('rapports.index', compact('stats', 'retards', 'top_immeubles', 'chartData'));
    }

    public function mensuel(Request $request)
    {
        $mois = $request->input('mois', now()->month);
        $annee = $request->input('annee', now()->year);

        // Loyers du mois
        $loyers = Loyer::with(['appartement.immeuble', 'locataire', 'paiements'])
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->get();

        // Statistiques
        $stats = [
            'total_loyers' => $loyers->sum('montant'),
            'loyers_payes' => $loyers->where('statut', 'paye')->sum('montant'),
            'loyers_impayes' => $loyers->where('statut', 'impaye')->sum('montant'),
            'loyers_partiels' => $loyers->where('statut', 'partiel')->sum('montant'),
            'nombre_payes' => $loyers->where('statut', 'paye')->count(),
            'nombre_impayes' => $loyers->where('statut', 'impaye')->count(),
            'nombre_partiels' => $loyers->where('statut', 'partiel')->count(),
        ];

        // Paiements du mois
        $paiements = Paiement::with(['locataire', 'loyer.appartement'])
            ->whereHas('loyer', function($query) use ($mois, $annee) {
                $query->where('mois', $mois)->where('annee', $annee);
            })
            ->where('est_annule', false)
            ->get();

        // Grouper par mode de paiement
        $paiementsParMode = $paiements->groupBy('mode_paiement')->map(function($group) {
            return [
                'nombre' => $group->count(),
                'montant' => $group->sum('montant')
            ];
        });

        return view('rapports.mensuel', compact('loyers', 'paiements', 'stats', 'paiementsParMode', 'mois', 'annee'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'mensuel');
        $format = $request->input('format', 'pdf');
        $mois = $request->input('mois', now()->month);
        $annee = $request->input('annee', now()->year);

        switch ($type) {
            case 'mensuel':
                return $this->exportMensuel($format, $mois, $annee);
            case 'factures_impayees':
                return $this->exportFacturesImpayees($format);
            case 'tresorerie':
                return $this->exportTresorerie($format);
            default:
                return back()->with('error', 'Type de rapport non valide.');
        }
    }

    private function exportMensuel($format, $mois, $annee)
    {
        $loyers = Loyer::with(['appartement.immeuble', 'locataire', 'paiements'])
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->get();

        $stats = [
            'total_loyers' => $loyers->sum('montant'),
            'loyers_payes' => $loyers->where('statut', 'paye')->sum('montant'),
            'loyers_impayes' => $loyers->where('statut', 'impaye')->sum('montant'),
            'nombre_payes' => $loyers->where('statut', 'paye')->count(),
            'nombre_impayes' => $loyers->where('statut', 'impaye')->count(),
        ];

        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        $data = compact('loyers', 'stats', 'mois', 'annee', 'nomMois');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('rapports.pdf.mensuel', $data);
            return $pdf->download("rapport_mensuel_{$mois}_{$annee}.pdf");
        } else {
            return Excel::download(new \App\Exports\RapportMensuelExport($data), "rapport_mensuel_{$mois}_{$annee}.xlsx");
        }
    }

    private function exportFacturesImpayees($format)
    {
        $facturesImpayees = Loyer::with(['appartement.immeuble', 'locataire'])
            ->where('statut', '!=', 'paye')
            ->orderBy('date_echeance', 'asc')
            ->get();

        $data = compact('facturesImpayees');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('rapports.pdf.factures_impayees', $data);
            return $pdf->download('factures_impayees_' . now()->format('Y_m_d') . '.pdf');
        } else {
            return Excel::download(new \App\Exports\FacturesImpayeesExport($data), 'factures_impayees_' . now()->format('Y_m_d') . '.xlsx');
        }
    }

    private function exportTresorerie($format)
    {
        $comptes = CompteFinancier::all();
        $soldeTotal = $comptes->sum('solde_actuel');

        // Mouvements du mois courant
        $mouvements = \App\Models\MouvementCaisse::with(['compteSource', 'compteDestination'])
            ->whereMonth('date_operation', now()->month)
            ->whereYear('date_operation', now()->year)
            ->where('est_annule', false)
            ->orderBy('date_operation', 'desc')
            ->get();

        $entrees = $mouvements->where('type_mouvement', 'entree')->sum('montant');
        $sorties = $mouvements->where('type_mouvement', 'sortie')->sum('montant');

        $data = compact('comptes', 'soldeTotal', 'mouvements', 'entrees', 'sorties');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('rapports.pdf.tresorerie', $data);
            return $pdf->download('rapport_tresorerie_' . now()->format('Y_m_d') . '.pdf');
        } else {
            return Excel::download(new \App\Exports\TresorerieExport($data), 'rapport_tresorerie_' . now()->format('Y_m_d') . '.xlsx');
        }
    }

    public function facturesImpayees()
    {
        $facturesImpayees = Loyer::with(['appartement.immeuble', 'locataire'])
            ->where('statut', '!=', 'paye')
            ->orderBy('date_echeance', 'asc')
            ->paginate(20);

        return view('rapports.factures_impayees', compact('facturesImpayees'));
    }

    public function facturePdf(Loyer $loyer)
    {
        $loyer->load(['appartement.immeuble', 'locataire', 'paiements']);
        
        $data = compact('loyer');
        
        $pdf = PDF::loadView('rapports.pdf.facture', $data);
        
        $nomFichier = "facture_{$loyer->locataire->nom}_{$loyer->mois}_{$loyer->annee}.pdf";
        
        return $pdf->download($nomFichier);
    }
}