<?php

namespace App\Http\Controllers;

use App\Models\Immeuble;
use Illuminate\Http\Request;

class ImmeubleeController extends Controller
{
    public function __construct()
    {
        $this->middleware('gestionnaire');
        $this->middleware('admin')->only(['destroy']);
    }

    public function index()
    {
        $immeubles = Immeuble::with('appartements')->paginate(30);
        return view('immeubles.index', compact('immeubles'));
    }

    public function create()
    {
        return view('immeubles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $immeuble = Immeuble::create($validated);

        return redirect()->route('immeubles.index')
            ->with('success', 'Immeuble créé avec succès.');
    }

    public function show(Immeuble $immeuble)
    {
        $immeuble->load(['appartements.locataire', 'appartements.loyers']);
        
        $stats = [
            'total_appartements' => $immeuble->nombreAppartements(),
            'appartements_libres' => $immeuble->appartementLibres(),
            'appartements_occupes' => $immeuble->appartementOccupes(),
            'revenus_totaux' => $immeuble->revenus()
        ];

        return view('immeubles.show', compact('immeuble', 'stats'));
    }

    public function edit(Immeuble $immeuble)
    {
        return view('immeubles.edit', compact('immeuble'));
    }

    public function update(Request $request, Immeuble $immeuble)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $immeuble->update($validated);

        return redirect()->route('immeubles.index')
            ->with('success', 'Immeuble modifié avec succès.');
    }

    public function destroy(Immeuble $immeuble)
    {
        if ($immeuble->appartements()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un immeuble qui contient des appartements.');
        }

        $immeuble->delete();

        return redirect()->route('immeubles.index')
            ->with('success', 'Immeuble supprimé avec succès.');
    }
}