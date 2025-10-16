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
        $query = Locataire::query();
        if (request('nom')) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . request('nom') . '%')
                  ->orWhere('prenom', 'like', '%' . request('nom') . '%');
            });
        }
        if (request('numero')) {
            $query->where(function($q) {
                $q->where('telephone', 'like', '%' . request('numero') . '%')
                  ->orWhere('numero_carte_identite', 'like', '%' . request('numero') . '%');
            });
        }
    $locataires = $query->with('appartement')->paginate(10);
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
            'date_naissance' => 'nullable|date',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:locataires,email',
            'adresse' => 'nullable|string',
            'profession' => 'nullable|string|max:255',
            'employeur' => 'nullable|string|max:255',
            'revenu_mensuel' => 'nullable|numeric|min:0',
            'numero_carte_identite' => 'nullable|string|max:255',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'actif' => 'boolean',
        ]);

        // Définir actif à true par défaut si non fourni
        $validated['actif'] = $request->has('actif') ? 1 : 0;

        $locataire = Locataire::create($validated);

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
            'email' => 'nullable|email|unique:locataires,email,' . $locataire->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'profession' => 'nullable|string|max:255',
            'salaire' => 'nullable|numeric|min:0',
            'appartement_id' => 'nullable|exists:appartements,id',
            'date_entree' => 'nullable|date',
            'garantie' => 'nullable|numeric|min:0',
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