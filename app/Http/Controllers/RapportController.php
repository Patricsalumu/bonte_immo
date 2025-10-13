<?php

namespace App\Http\Controllers;

use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\CompteFinancier;
use App\Models\Appartement;
use App\Models\Locataire;
use App\Models\Facture;
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
        // Redirige vers la vue mensuelle par défaut (mois/année courants)
        $mois = now()->month;
        $annee = now()->year;
        return redirect()->route('rapports.mensuel', ['mois' => $mois, 'annee' => $annee]);
    }

    public function mensuel(Request $request)
    {
    $nomMois = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];

    $mois = $request->input('mois', now()->month);
    $annee = $request->input('annee', now()->year);

        // Factures du mois
        $factures = Facture::with(['appartement.immeuble', 'locataire', 'paiements'])
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->get();

        // Statistiques
        $stats = [
            'total_factures' => $factures->sum('montant'),
            'montant_payes' => $factures->sum('montant_paye'),
            'reste_a_payer' => $factures->sum('montant') - $factures->sum('montant_paye'),
            'nombre_factures' => $factures->count(),
        ];

    return view('rapports.mensuel', compact('factures', 'stats', 'mois', 'annee', 'nomMois'));
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
        $factures = Facture::with(['appartement.immeuble', 'locataire', 'paiements.utilisateur'])
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->get();

        // Calculs dynamiques pour chaque facture
        foreach ($factures as $facture) {
            $paiementsValides = $facture->paiements->where('est_annule', false);
            $facture->montant_payes_calc = $paiementsValides->sum('montant');
            $dernierPaiement = $paiementsValides->sortByDesc('created_at')->first();
            $facture->date_paiement_calc = $dernierPaiement ? $dernierPaiement->created_at : null;
            $facture->percepteur_calc = $dernierPaiement && $dernierPaiement->utilisateur ? $dernierPaiement->utilisateur->name : null;
        }

        $stats = [
            'total_factures' => $factures->sum('montant'),
            'montant_payes' => $factures->sum('montant_payes_calc'),
            'reste_a_payer' => $factures->sum('montant') - $factures->sum('montant_payes_calc'),
            'nombre_factures' => $factures->count(),
        ];
        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        $data = compact('factures', 'stats', 'mois', 'annee', 'nomMois');

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