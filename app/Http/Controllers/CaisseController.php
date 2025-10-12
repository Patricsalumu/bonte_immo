<?php

namespace App\Http\Controllers;

use App\Models\CompteFinancier;
use App\Models\MouvementCaisse;
use Illuminate\Http\Request;

class CaisseController extends Controller
{
    public function __construct()
    {
        $this->middleware('gestionnaire');
        $this->middleware('admin')->only(['create', 'store', 'transfert', 'executeTransfert', 'annuler']);
    }

    public function index()
    {
        $comptes = CompteFinancier::where('actif', true)->get();
                $soldeTotal = $comptes->sum('solde_actuel');
        
        $mouvementsRecents = MouvementCaisse::with(['compteSource', 'compteDestination', 'utilisateur'])
            ->where('est_annule', false)
            ->orderBy('date_operation', 'desc')
            ->take(10)
            ->get();

        return view('caisse.index', compact('comptes', 'soldeTotal', 'mouvementsRecents'));
    }

    public function journal(Request $request)
    {
        $query = MouvementCaisse::with(['compteSource', 'compteDestination', 'utilisateur'])
            ->where('est_annule', false);

        // Filtres
        if ($request->filled('compte_id')) {
            $query->where(function($q) use ($request) {
                $q->where('compte_source_id', $request->compte_id)
                  ->orWhere('compte_destination_id', $request->compte_id);
            });
        }

        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_operation', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_operation', '<=', $request->date_fin);
        }

        $mouvements = $query->orderBy('date_operation', 'desc')->paginate(20);
        $comptes = CompteFinancier::where('actif', true)->get();

        // Calculer les statistiques
        $statistiques = [
            'total_entrees' => MouvementCaisse::where('type_mouvement', 'entree')
                ->where('est_annule', false)
                ->sum('montant'),
            'total_sorties' => MouvementCaisse::where('type_mouvement', 'sortie')
                ->where('est_annule', false)
                ->sum('montant'),
            'total_transferts' => MouvementCaisse::where('type_mouvement', 'transfert')
                ->where('est_annule', false)
                ->sum('montant'),
            'solde_net' => 0
        ];
        
        $statistiques['solde_net'] = $statistiques['total_entrees'] - $statistiques['total_sorties'];

        return view('caisse.journal', compact('mouvements', 'comptes', 'statistiques'));
    }

    public function create()
    {
        $comptes = CompteFinancier::all();
        return view('caisse.create', compact('comptes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_mouvement' => 'required|in:entree,sortie',
            'compte_id' => 'required|exists:comptes_financiers,id',
            'montant' => 'required|numeric|min:0.01',
            'mode_paiement' => 'nullable|string',
            'description' => 'required|string|max:1000',
            'categorie' => 'nullable|string|max:255',
            'date_operation' => 'required|date',
        ]);

        $compte = CompteFinancier::findOrFail($validated['compte_id']);

        // Vérifier si le compte a suffisamment de fonds pour une sortie
        if ($validated['type_mouvement'] === 'sortie' && $compte->solde_actuel < $validated['montant']) {
            return back()->with('error', 'Solde insuffisant dans le compte sélectionné.');
        }

        $mouvement = MouvementCaisse::create([
            'compte_source_id' => $validated['type_mouvement'] === 'sortie' ? $validated['compte_id'] : null,
            'compte_destination_id' => $validated['type_mouvement'] === 'entree' ? $validated['compte_id'] : null,
            'type_mouvement' => $validated['type_mouvement'],
            'montant' => $validated['montant'],
            'mode_paiement' => $validated['mode_paiement'],
            'description' => $validated['description'],
            'categorie' => $validated['categorie'],
            'utilisateur_id' => auth()->id(),
            'date_operation' => $validated['date_operation'],
        ]);

        // Mettre à jour le solde du compte
        if ($validated['type_mouvement'] === 'entree') {
            $compte->crediter($validated['montant']);
        } else {
            $compte->debiter($validated['montant']);
        }

        return redirect()->route('caisse.index')
            ->with('success', 'Mouvement enregistré avec succès.');
    }

    public function transfert()
    {
        $comptes = CompteFinancier::all();
        return view('caisse.transfert', compact('comptes'));
    }

    public function executeTransfert(Request $request)
    {
        $validated = $request->validate([
            'compte_source_id' => 'required|exists:comptes_financiers,id',
            'compte_destination_id' => 'required|exists:comptes_financiers,id|different:compte_source_id',
            'montant' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'date_operation' => 'required|date',
        ]);

        $compteSource = CompteFinancier::findOrFail($validated['compte_source_id']);
        $compteDestination = CompteFinancier::findOrFail($validated['compte_destination_id']);

        // Vérifier si le compte source a suffisamment de fonds
        if ($compteSource->solde_actuel < $validated['montant']) {
            return back()->with('error', 'Solde insuffisant dans le compte source.');
        }

        // Effectuer le transfert
        $compteSource->transfererVers(
            $compteDestination,
            $validated['montant'],
            $validated['description'],
            auth()->user()
        );

        return redirect()->route('caisse.index')
            ->with('success', 'Transfert effectué avec succès.');
    }

    public function annuler(MouvementCaisse $mouvement)
    {
        if ($mouvement->est_annule) {
            return back()->with('error', 'Ce mouvement est déjà annulé.');
        }

        $mouvement->annuler();

        return back()->with('success', 'Mouvement annulé avec succès.');
    }
}