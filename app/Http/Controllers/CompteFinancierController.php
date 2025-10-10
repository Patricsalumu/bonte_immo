<?php

namespace App\Http\Controllers;

use App\Models\CompteFinancier;
use Illuminate\Http\Request;

class CompteFinancierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $comptes = CompteFinancier::all();
        return view('comptes-financiers.index', compact('comptes'));
    }

    public function create()
    {
        return view('comptes-financiers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:caisse,banque,epargne',
            'solde' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        CompteFinancier::create($validated);

        return redirect()->route('comptes-financiers.index')
                        ->with('success', 'Compte financier créé avec succès.');
    }

    public function show(CompteFinancier $compteFinancier)
    {
        $compteFinancier->load('mouvementsSource', 'mouvementsDestination');
        return view('comptes-financiers.show', compact('compteFinancier'));
    }

    public function edit(CompteFinancier $compteFinancier)
    {
        return view('comptes-financiers.edit', compact('compteFinancier'));
    }

    public function update(Request $request, CompteFinancier $compteFinancier)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:caisse,banque,epargne',
            'solde' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $compteFinancier->update($validated);

        return redirect()->route('comptes-financiers.index')
                        ->with('success', 'Compte financier mis à jour avec succès.');
    }

    public function destroy(CompteFinancier $compteFinancier)
    {
        // Vérifier qu'il n'y a pas de mouvements liés
        if ($compteFinancier->mouvementsSource()->count() > 0 || 
            $compteFinancier->mouvementsDestination()->count() > 0) {
            return redirect()->route('comptes-financiers.index')
                            ->with('error', 'Impossible de supprimer ce compte car il a des mouvements associés.');
        }

        $compteFinancier->delete();

        return redirect()->route('comptes-financiers.index')
                        ->with('success', 'Compte financier supprimé avec succès.');
    }
}