<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Immeuble;
use Illuminate\Http\Request;

class AppartementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('gestionnaire');
    }

    public function index()
    {
        $appartements = Appartement::with(['immeuble', 'locataire'])->get();
        return view('appartements.index', compact('appartements'));
    }

    public function create()
    {
        $immeubles = Immeuble::all();
        return view('appartements.create', compact('immeubles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'immeuble_id' => 'required|exists:immeubles,id',
            'numero' => 'required|string|max:10',
            'type' => 'required|in:studio,1_chambre,2_chambres,3_chambres,4_chambres_plus,duplex',
            'superficie' => 'nullable|numeric|min:1',
            'etage' => 'nullable|integer|min:0',
            'loyer_mensuel' => 'required|numeric|min:0',
            'garantie_locative' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'meuble' => 'nullable|boolean',
            'disponible' => 'nullable|boolean',
        ]);

        Appartement::create($validated);

        return redirect()->route('appartements.index')
                        ->with('success', 'Appartement créé avec succès.');
    }

    public function show(Appartement $appartement)
    {
        $appartement->load([
            'immeuble', 
            'locataire', 
            'loyers' => function($query) {
                $query->with(['factures' => function($factureQuery) {
                    $factureQuery->with('paiements');
                }]);
            }
        ]);
        
        // Calculer les statistiques basées sur les factures
        $factures = $appartement->loyers->flatMap->factures;
        $facturesPayees = $factures->where('statut_paiement', 'payee');
        $facturesNonPayees = $factures->where('statut_paiement', 'non_payee');
        
        $montantTotalPaye = $facturesPayees->sum('montant');
        $montantTotalDu = $facturesNonPayees->sum('montant');
        
        return view('appartements.show', compact('appartement', 'montantTotalPaye', 'montantTotalDu'));
    }

    public function edit(Appartement $appartement)
    {
        $immeubles = Immeuble::all();
        $locataires = \App\Models\Locataire::all();
        return view('appartements.edit', compact('appartement', 'immeubles', 'locataires'));
    }

    public function update(Request $request, Appartement $appartement)
    {
        $validated = $request->validate([
            'immeuble_id' => 'required|exists:immeubles,id',
            'numero' => 'required|string|max:10',
            'type' => 'required|in:studio,1_chambre,2_chambres,3_chambres,4_chambres_plus,duplex',
            'superficie' => 'nullable|numeric|min:1',
            'etage' => 'nullable|integer|min:0',
            'loyer_mensuel' => 'required|numeric|min:0',
            'garantie_locative' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'meuble' => 'nullable|boolean',
            'disponible' => 'nullable|boolean',
        ]);

        $appartement->update($validated);

        return redirect()->route('appartements.index')
                        ->with('success', 'Appartement mis à jour avec succès.');
    }
}