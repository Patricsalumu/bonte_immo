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
        $loyers = Loyer::with(['appartement.immeuble', 'locataire'])->orderBy('created_at', 'desc')->get();
        return view('loyers.index', compact('loyers'));
    }

    public function create()
    {
        // Récupérer seulement les appartements disponibles (sans contrat actif)
        $appartements = Appartement::disponibles()->with('immeuble')->get();
        
        // Récupérer seulement les locataires disponibles (sans contrat actif)
        $locataires = Locataire::disponibles()->get();
        
        return view('loyers.create', compact('appartements', 'locataires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appartement_id' => 'required|exists:appartements,id',
            'locataire_id' => 'required|exists:locataires,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'montant' => 'required|numeric|min:0',
            'garantie_locative' => 'required|numeric|min:1', // Garantie obligatoire
            'notes' => 'nullable|string',
        ]);

        // Vérifier que l'appartement est disponible
        $appartement = Appartement::find($validated['appartement_id']);
        if (!$appartement->estDisponible()) {
            return back()->withErrors(['appartement_id' => 'Cet appartement a déjà un contrat actif.'])->withInput();
        }

        // Vérifier que le locataire est disponible
        $locataire = Locataire::find($validated['locataire_id']);
        if (!$locataire->estDisponible()) {
            return back()->withErrors(['locataire_id' => 'Ce locataire a déjà un contrat actif.'])->withInput();
        }

        // Vérifier le paiement de la garantie
        $montantGarantie = $validated['garantie_locative'];
        if ($montantGarantie < 1) {
            return back()->withErrors(['garantie_locative' => 'Le paiement de la garantie est obligatoire.'])->withInput();
        }

        // Créer le contrat de loyer
        $loyer = Loyer::create([
            'appartement_id' => $validated['appartement_id'],
            'locataire_id' => $validated['locataire_id'],
            'montant' => $validated['montant'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'garantie_locative' => $montantGarantie,
            'notes' => $validated['notes'],
            'statut' => 'actif',
        ]);

        // Mouvement caisse pour la garantie
        $compteUtilisateur = auth()->user()->compte_financier_id ? \App\Models\CompteFinancier::find(auth()->user()->compte_financier_id) : null;
        if (!$compteUtilisateur) {
            // Suppression du loyer créé si le paiement échoue
            $loyer->delete();
            return back()->withErrors(['error' => "Le paiement de la garantie a échoué car aucun compte à débiter n'est configuré. Veuillez d'abord configurer un compte dans votre profil utilisateur."])->withInput();
        }
        \App\Models\MouvementCaisse::create([
            'compte_destination_id' => $compteUtilisateur->id,
            'type_mouvement' => 'entree',
            'montant' => $montantGarantie,
            'mode_paiement' => 'garantie_locative',
            'description' => 'Paiement garantie locative à la création du loyer',
            'categorie' => 'garantie_locative',
            'utilisateur_id' => auth()->id(),
            'date_operation' => now(),
            'est_annule' => false,
        ]);
    // $compteUtilisateur->increment('solde', $montantGarantie); // supprimé, on ne garde que solde_actuel
        $compteUtilisateur->increment('solde_actuel', $montantGarantie);

        // Enregistrer le paiement de garantie dans la table paiements
        \App\Models\Paiement::create([
            'facture_id' => null,
            'loyer_id' => $loyer->id,
            'locataire_id' => $locataire->id,
            'montant' => $montantGarantie,
            'date_paiement' => now(),
            'mode_paiement' => 'cash',
            'reference_paiement' => null,
            'utilisateur_id' => auth()->id(),
            'est_annule' => false,
            'notes' => 'Paiement de la garantie locative à la création du loyer',
        ]);

        // Mettre à jour la garantie initiale du locataire
        $locataire->update(['garantie_initiale' => $montantGarantie]);

        // Marquer l'appartement comme occupé et assigner le locataire
        $appartement->update([
            'statut' => 'occupe',
            'locataire_id' => $validated['locataire_id']
        ]);

        return redirect()->route('loyers.index')
                        ->with('success', 'Contrat de loyer créé avec succès. Garantie encaissée.');
    }

    public function show(Loyer $loyer)
    {
        $loyer->load(['appartement.immeuble', 'locataire', 'factures.paiements']);
        return view('loyers.show', compact('loyer'));
    }

    public function edit(Loyer $loyer)
    {
        $appartements = Appartement::with('immeuble')->get();
        $locataires = Locataire::all();
        return view('loyers.edit', compact('loyer', 'appartements', 'locataires'));
    }

    public function update(Request $request, Loyer $loyer)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:0',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'garantie_locative' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'statut' => 'required|in:actif,inactif',
        ]);

        $loyer->update($validated);

        return redirect()->route('loyers.index')
                        ->with('success', 'Contrat mis à jour avec succès.');
    }

    public function destroy(Loyer $loyer)
    {
        // Libérer l'appartement si c'était le dernier contrat actif
        if ($loyer->estActif()) {
            $loyer->appartement->liberer();
        }
        
        $loyer->delete();
        
        return redirect()->route('loyers.index')
                        ->with('success', 'Contrat supprimé avec succès.');
    }

    public function desactiver(Loyer $loyer)
    {
        // Vérifier s'il existe des factures non payées pour ce locataire et ce loyer
        $facturesImpayees = $loyer->factures()->where('statut_paiement', 'non_paye')->count();
        if ($facturesImpayees > 0) {
            return redirect()->back()->with('error', 'Impossible de désactiver ce contrat : il existe des factures non payées pour ce locataire.');
        }
        $loyer->desactiver('Contrat désactivé manuellement');
        $loyer->appartement->liberer();

        return redirect()->route('loyers.index')
                        ->with('success', 'Contrat désactivé avec succès.');
    }
}