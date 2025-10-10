<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Loyer;
use App\Models\CompteFinancier;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('gestionnaire');
    }

    public function index()
    {
        $paiements = Paiement::with(['loyer.appartement', 'loyer.locataire', 'utilisateur'])->orderBy('date_paiement', 'desc')->get();
        return view('paiements.index', compact('paiements'));
    }

    public function create()
    {
        $loyers = Loyer::where('statut', '!=', 'paye')->with(['appartement', 'locataire'])->get();
        $comptes = CompteFinancier::all();
        return view('paiements.create', compact('loyers', 'comptes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loyer_id' => 'required|exists:loyers,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:especes,cheque,virement,mobile_money',
            'reference' => 'nullable|string|max:255',
            'date_paiement' => 'required|date',
            'compte_id' => 'required|exists:comptes_financiers,id',
            'observations' => 'nullable|string',
        ]);

        $validated['utilisateur_id'] = auth()->id();

        $paiement = Paiement::create($validated);

        // Mettre à jour le solde du compte
        $compte = CompteFinancier::find($validated['compte_id']);
        $compte->increment('solde', $validated['montant']);

        // Vérifier si le loyer est complètement payé
        $loyer = Loyer::find($validated['loyer_id']);
        $totalPaye = $loyer->paiements()->sum('montant');
        
        if ($totalPaye >= $loyer->montant) {
            $loyer->update(['statut' => 'paye']);
        } elseif ($totalPaye > 0) {
            $loyer->update(['statut' => 'partiel']);
        }

        return redirect()->route('paiements.index')
                        ->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['loyer.appartement', 'loyer.locataire', 'utilisateur']);
        return view('paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement)
    {
        $loyers = Loyer::with(['appartement', 'locataire'])->get();
        $comptes = CompteFinancier::all();
        return view('paiements.edit', compact('paiement', 'loyers', 'comptes'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([
            'loyer_id' => 'required|exists:loyers,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:especes,cheque,virement,mobile_money',
            'reference' => 'nullable|string|max:255',
            'date_paiement' => 'required|date',
            'compte_id' => 'required|exists:comptes_financiers,id',
            'observations' => 'nullable|string',
        ]);

        // Ajuster le solde du compte (retirer l'ancien montant, ajouter le nouveau)
        $ancienCompte = CompteFinancier::find($paiement->compte_id);
        $nouveauCompte = CompteFinancier::find($validated['compte_id']);
        
        $ancienCompte->decrement('solde', $paiement->montant);
        $nouveauCompte->increment('solde', $validated['montant']);

        $paiement->update($validated);

        // Recalculer le statut du loyer
        $loyer = Loyer::find($validated['loyer_id']);
        $totalPaye = $loyer->paiements()->sum('montant');
        
        if ($totalPaye >= $loyer->montant) {
            $loyer->update(['statut' => 'paye']);
        } elseif ($totalPaye > 0) {
            $loyer->update(['statut' => 'partiel']);
        } else {
            $loyer->update(['statut' => 'impaye']);
        }

        return redirect()->route('paiements.index')
                        ->with('success', 'Paiement mis à jour avec succès.');
    }
}