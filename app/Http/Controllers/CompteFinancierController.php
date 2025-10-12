<?php

namespace App\Http\Controllers;

use App\Models\CompteFinancier;
use App\Models\User;
use App\Models\MouvementCaisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompteFinancierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $comptes = CompteFinancier::with('gestionnaire')->paginate(15);
        
        // Calculer les statistiques par type
        $statistiques = [
            'caisse' => [
                'nombre' => CompteFinancier::where('type', 'caisse')->count(),
                'solde' => CompteFinancier::where('type', 'caisse')->sum('solde_actuel')
            ],
            'banque' => [
                'nombre' => CompteFinancier::where('type', 'banque')->count(),
                'solde' => CompteFinancier::where('type', 'banque')->sum('solde_actuel')
            ],
            'epargne' => [
                'nombre' => CompteFinancier::where('type', 'epargne')->count(),
                'solde' => CompteFinancier::where('type', 'epargne')->sum('solde_actuel')
            ],
            'charge' => [
                'nombre' => CompteFinancier::where('type', 'charge')->count(),
                'solde' => CompteFinancier::where('type', 'charge')->sum('solde_actuel')
            ]
        ];
        
        return view('comptes-financiers.index', compact('comptes', 'statistiques'));
    }

    public function create()
    {
        $utilisateurs = User::all();
        return view('comptes-financiers.create', compact('utilisateurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:comptes_financiers,nom',
            'type' => 'required|in:caisse,banque,epargne,charge',
            'solde_initial' => 'required|numeric|min:0',
            'gestionnaire_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'actif' => 'nullable|boolean',
            'autoriser_decouvert' => 'nullable|boolean',
        ]);

        // Préparer les données pour la création
        $compteData = [
            'nom' => $validated['nom'],
            'type' => $validated['type'],
            'solde_actuel' => $validated['solde_initial'],
            'gestionnaire_id' => $validated['gestionnaire_id'],
            'description' => $validated['description'],
            'actif' => $request->has('actif'),
            'autoriser_decouvert' => $request->has('autoriser_decouvert'),
        ];

        DB::transaction(function () use ($compteData, $validated) {
            $compte = CompteFinancier::create($compteData);

            // Si le solde initial est > 0, créer un mouvement d'ouverture
            if ($validated['solde_initial'] > 0) {
                MouvementCaisse::create([
                    'compte_destination_id' => $compte->id,
                    'montant' => $validated['solde_initial'],
                    'type' => 'entree',
                    'description' => 'Solde d\'ouverture du compte',
                    'utilisateur_id' => auth()->id(),
                    'date_mouvement' => now(),
                ]);
            }
        });

        return redirect()->route('caisse.index')
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