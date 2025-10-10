<?php

namespace App\Http\Controllers;

use App\Models\Loyer;
use App\Models\Appartement;
use App\Models\Locataire;
use Illuminate\Http\Request;

class LoyerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('gestionnaire');
    }

    public function index()
    {
        $loyers = Loyer::with(['appartement', 'locataire'])->orderBy('date_echeance', 'desc')->get();
        return view('loyers.index', compact('loyers'));
    }

    public function create()
    {
        $appartements = Appartement::with('immeuble')->get();
        $locataires = Locataire::where('actif', 1)->get();
        return view('loyers.create', compact('appartements', 'locataires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appartement_id' => 'required|exists:appartements,id',
            'locataire_id' => 'required|exists:locataires,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2020',
            'montant' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
            'garantie_restante' => 'nullable|numeric|min:0',
        ]);

        // Associer le locataire à l'appartement s'il ne l'est pas déjà
        $appartement = Appartement::find($validated['appartement_id']);
        if (!$appartement->locataire_id) {
            $appartement->update(['locataire_id' => $validated['locataire_id']]);
        }

        Loyer::create($validated);

        return redirect()->route('loyers.index')
                        ->with('success', 'Loyer créé avec succès.');
    }

    public function show(Loyer $loyer)
    {
        $loyer->load(['appartement', 'locataire', 'paiements']);
        return view('loyers.show', compact('loyer'));
    }

    public function edit(Loyer $loyer)
    {
        $appartements = Appartement::whereNotNull('locataire_id')->with('locataire')->get();
        return view('loyers.edit', compact('loyer', 'appartements'));
    }

    public function update(Request $request, Loyer $loyer)
    {
        $validated = $request->validate([
            'appartement_id' => 'required|exists:appartements,id',
            'locataire_id' => 'required|exists:locataires,id',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|min:2020',
            'montant' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
            'garantie_restante' => 'nullable|numeric|min:0',
        ]);

        $loyer->update($validated);

        return redirect()->route('loyers.index')
                        ->with('success', 'Loyer mis à jour avec succès.');
    }

    public function marquerPaye(Loyer $loyer)
    {
        $loyer->update(['statut' => 'paye']);

        return redirect()->route('loyers.index')
                        ->with('success', 'Loyer marqué comme payé.');
    }
}