<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\Appartement;
use Illuminate\Http\Request;

class LocataireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('gestionnaire');
    }

    public function index()
    {
        $locataires = Locataire::with('appartement')->get();
        return view('locataires.index', compact('locataires'));
    }

    public function create()
    {
        $appartements = Appartement::whereNull('locataire_id')->get();
        return view('locataires.create', compact('appartements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:locataires,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'date_naissance' => 'required|date',
            'profession' => 'nullable|string|max:255',
            'salaire' => 'nullable|numeric|min:0',
            'appartement_id' => 'required|exists:appartements,id',
            'date_entree' => 'required|date',
            'garantie' => 'required|numeric|min:0',
            'observations' => 'nullable|string',
        ]);

        $locataire = Locataire::create($validated);

        // Associer le locataire à l'appartement
        $appartement = Appartement::find($validated['appartement_id']);
        $appartement->update(['locataire_id' => $locataire->id]);

        return redirect()->route('locataires.index')
                        ->with('success', 'Locataire créé avec succès.');
    }

    public function show(Locataire $locataire)
    {
        $locataire->load(['appartement', 'loyers', 'paiements']);
        return view('locataires.show', compact('locataire'));
    }

    public function edit(Locataire $locataire)
    {
        $appartements = Appartement::whereNull('locataire_id')
                                  ->orWhere('locataire_id', $locataire->id)
                                  ->get();
        return view('locataires.edit', compact('locataire', 'appartements'));
    }

    public function update(Request $request, Locataire $locataire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:locataires,email,' . $locataire->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'date_naissance' => 'required|date',
            'profession' => 'nullable|string|max:255',
            'salaire' => 'nullable|numeric|min:0',
            'appartement_id' => 'required|exists:appartements,id',
            'date_entree' => 'required|date',
            'garantie' => 'required|numeric|min:0',
            'observations' => 'nullable|string',
        ]);

        // Mettre à jour l'association avec l'appartement
        if ($locataire->appartement_id != $validated['appartement_id']) {
            // Libérer l'ancien appartement
            if ($locataire->appartement) {
                $locataire->appartement->update(['locataire_id' => null]);
            }
            
            // Associer au nouveau appartement
            $appartement = Appartement::find($validated['appartement_id']);
            $appartement->update(['locataire_id' => $locataire->id]);
        }

        $locataire->update($validated);

        return redirect()->route('locataires.index')
                        ->with('success', 'Locataire mis à jour avec succès.');
    }
}