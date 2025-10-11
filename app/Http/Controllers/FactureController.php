<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Loyer;
use App\Models\Locataire;
use App\Models\Immeuble;
use App\Models\Paiement;
use App\Models\CompteCaisse;
use Carbon\Carbon;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Facture::with(['locataire', 'loyer.appartement.immeuble']);

        // Filtres
        if ($request->filled('mois')) {
            $query->where('mois', $request->mois);
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('statut')) {
            $query->where('statut_paiement', $request->statut);
        }

        if ($request->filled('immeuble_id')) {
            $query->whereHas('loyer.appartement', function($q) use ($request) {
                $q->where('immeuble_id', $request->immeuble_id);
            });
        }

        if ($request->filled('locataire_id')) {
            $query->where('locataire_id', $request->locataire_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhereHas('locataire', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
        $sortField = $request->get('sort', 'date_echeance');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $factures = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Facture::count(),
            'non_payees' => Facture::where('statut_paiement', 'non_paye')->count(),
            'en_retard' => Facture::enRetard()->count(),
            'payees' => Facture::payees()->count(),
            'montant_total' => Facture::sum('montant'),
            'montant_paye' => Facture::sum('montant_paye'),
            'montant_impaye' => Facture::where('statut_paiement', 'non_paye')->sum('montant')
        ];

        // Data pour les filtres
        $immeubles = Immeuble::orderBy('nom')->get();
        $locataires = Locataire::orderBy('nom')->get();
        $annees = Facture::selectRaw('DISTINCT annee')->orderBy('annee', 'desc')->pluck('annee');

        return view('factures.index', compact('factures', 'stats', 'immeubles', 'locataires', 'annees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loyers = Loyer::with(['locataire', 'appartement.immeuble'])
                      ->where('statut', 'actif')
                      ->get();

        return view('factures.create', compact('loyers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loyer_id' => 'required|exists:loyers,id',
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030',
            'montant' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Vérifier si une facture existe déjà
        $existante = Facture::where('loyer_id', $request->loyer_id)
                           ->where('mois', $request->mois)
                           ->where('annee', $request->annee)
                           ->first();

        if ($existante) {
            return back()->withErrors(['error' => 'Une facture existe déjà pour cette période.']);
        }

        $loyer = Loyer::findOrFail($request->loyer_id);

        $facture = Facture::create([
            'loyer_id' => $request->loyer_id,
            'locataire_id' => $loyer->locataire_id,
            'mois' => $request->mois,
            'annee' => $request->annee,
            'montant' => $request->montant,
            'date_echeance' => $request->date_echeance,
            'notes' => $request->notes
        ]);

        return redirect()->route('factures.show', $facture)
                        ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facture $facture)
    {
        $facture->load(['locataire', 'loyer.appartement.immeuble', 'paiements.compte']);

        return view('factures.show', compact('facture'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facture $facture)
    {
        if ($facture->est_payee) {
            return back()->withErrors(['error' => 'Impossible de modifier une facture payée.']);
        }

        $loyers = Loyer::with(['locataire', 'appartement.immeuble'])
                      ->where('statut', 'actif')
                      ->get();

        return view('factures.edit', compact('facture', 'loyers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facture $facture)
    {
        if ($facture->est_payee) {
            return back()->withErrors(['error' => 'Impossible de modifier une facture payée.']);
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $facture->update($request->only(['montant', 'date_echeance', 'notes']));

        return redirect()->route('factures.show', $facture)
                        ->with('success', 'Facture modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facture $facture)
    {
        if ($facture->est_payee) {
            return back()->withErrors(['error' => 'Impossible de supprimer une facture payée.']);
        }

        $facture->delete();

        return redirect()->route('factures.index')
                        ->with('success', 'Facture supprimée avec succès.');
    }

    /**
     * Marquer une facture comme payée
     */
    public function marquerPayee(Request $request, Facture $facture)
    {
        $request->validate([
            'montant_paye' => 'required|numeric|min:0|max:' . $facture->montant,
            'date_paiement' => 'required|date',
            'compte_id' => 'required|exists:comptes_caisses,id',
            'mode_paiement' => 'required|in:especes,cheque,virement,mobile_money',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        try {
            \DB::beginTransaction();

            // Marquer la facture comme payée
            $facture->marquerCommePayee($request->montant_paye, $request->date_paiement);

            // Créer l'écriture de paiement
            $paiement = Paiement::create([
                'facture_id' => $facture->id,
                'locataire_id' => $facture->locataire_id,
                'loyer_id' => $facture->loyer_id,
                'compte_id' => $request->compte_id,
                'montant' => $request->montant_paye,
                'date_paiement' => $request->date_paiement,
                'mode_paiement' => $request->mode_paiement,
                'reference_paiement' => $request->reference,
                'notes' => $request->notes
            ]);

            // Créditer le compte caisse
            $compte = CompteCaisse::findOrFail($request->compte_id);
            $compte->increment('solde', $request->montant_paye);

            \DB::commit();

            return redirect()->route('factures.show', $facture)
                           ->with('success', 'Paiement enregistré avec succès.');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Erreur lors de l\'enregistrement du paiement.']);
        }
    }

    /**
     * Générer les factures pour un mois donné
     */
    public function genererFacturesMois(Request $request)
    {
        $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030'
        ]);

        $mois = $request->mois;
        $annee = $request->annee;

        // Vérifier combien de factures existent déjà pour cette période
        $facturesExistantes = Facture::where('mois', $mois)
                                   ->where('annee', $annee)
                                   ->count();

        // Récupérer tous les loyers actifs
        $loyersActifs = Loyer::with(['locataire', 'appartement.immeuble'])
                            ->where('statut', 'actif')
                            ->count();

        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ][$mois];

        // Si toutes les factures existent déjà
        if ($facturesExistantes >= $loyersActifs && $loyersActifs > 0) {
            return back()->with('warning', 
                "Toutes les factures pour {$nomMois} {$annee} ont déjà été générées ({$facturesExistantes} factures)."
            );
        }

        try {
            $facturesCreees = Facture::genererFacturesPourMois($mois, $annee);

            $message = $facturesCreees > 0 
                ? "{$facturesCreees} nouvelle(s) facture(s) générée(s) pour {$nomMois} {$annee}"
                : "Aucune nouvelle facture à créer pour {$nomMois} {$annee}";

            $type = $facturesCreees > 0 ? 'success' : 'info';

            // Ajouter des détails sur les factures existantes si nécessaire
            if ($facturesExistantes > 0) {
                $message .= " ({$facturesExistantes} facture(s) existaient déjà)";
            }

            return back()->with($type, $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la génération des factures: ' . $e->getMessage()]);
        }
    }

    /**
     * Vérifier les doublons potentiels avant génération
     */
    public function verifierDoublons(Request $request)
    {
        $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030'
        ]);

        $mois = $request->mois;
        $annee = $request->annee;

        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ][$mois];

        // Compter les factures existantes
        $facturesExistantes = Facture::where('mois', $mois)
                                   ->where('annee', $annee)
                                   ->count();

        // Compter les loyers actifs
        $loyersActifs = Loyer::where('statut', 'actif')->count();

        // Facturer à créer
        $facturesACreer = max(0, $loyersActifs - $facturesExistantes);

        return response()->json([
            'periode' => "{$nomMois} {$annee}",
            'factures_existantes' => $facturesExistantes,
            'loyers_actifs' => $loyersActifs,
            'factures_a_creer' => $facturesACreer,
            'peut_generer' => $facturesACreer > 0
        ]);
    }

    /**
     * Exporter les factures
     */
    public function export(Request $request)
    {
        // À implémenter selon les besoins (PDF, Excel, etc.)
        // Pour l'instant, retourner simplement un JSON
        $factures = Facture::with(['locataire', 'loyer.appartement.immeuble'])->get();
        
        return response()->json($factures);
    }

    /**
     * Dashboard des factures avec statistiques
     */
    public function dashboard()
    {
        $moisCourant = now()->month;
        $anneeCourante = now()->year;

        $stats = [
            // Statistiques du mois courant
            'mois_courant' => [
                'total' => Facture::pourMois($moisCourant, $anneeCourante)->count(),
                'payees' => Facture::pourMois($moisCourant, $anneeCourante)->payees()->count(),
                'non_payees' => Facture::pourMois($moisCourant, $anneeCourante)->nonPayees()->count(),
                'montant_total' => Facture::pourMois($moisCourant, $anneeCourante)->sum('montant'),
                'montant_paye' => Facture::pourMois($moisCourant, $anneeCourante)->sum('montant_paye')
            ],
            
            // Factures en retard
            'en_retard' => [
                'count' => Facture::enRetard()->count(),
                'montant' => Facture::enRetard()->sum('montant')
            ],
            
            // Évolution sur 12 mois
            'evolution' => $this->getEvolutionFactures()
        ];

        return view('factures.dashboard', compact('stats'));
    }

    /**
     * Obtenir l'évolution des factures sur 12 mois
     */
    private function getEvolutionFactures()
    {
        $evolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois = $date->month;
            $annee = $date->year;
            
            $evolution[] = [
                'periode' => $date->format('M Y'),
                'total' => Facture::pourMois($mois, $annee)->count(),
                'payees' => Facture::pourMois($mois, $annee)->payees()->count(),
                'montant' => Facture::pourMois($mois, $annee)->sum('montant')
            ];
        }
        
        return $evolution;
    }

    /**
     * Exporter une facture en PDF
     */
    public function exportPdf(Facture $facture)
    {
        $facture->load(['locataire', 'loyer.appartement.immeuble']);
        
        // Données pour le PDF
        $data = [
            'facture' => $facture,
            'entreprise' => [
                'nom' => 'La Bonte Immo',
                'adresse' => 'Avenue de la révolution, Q. Industriel C. Lshi',
                'telephone' => '+243 000 000 000',
                'email' => 'contact@labonteimmo.cd'
            ],
            'date_generation' => now()->format('d/m/Y H:i')
        ];

        // Générer le PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('factures.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Nom du fichier
        $filename = "facture_{$facture->numero_facture}.pdf";

        return $pdf->download($filename);
    }
}
