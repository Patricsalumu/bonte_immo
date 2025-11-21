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
    // Charger aussi les loyers pour éviter des requêtes lazy et garantir les données utilisées en vue
    $query = Appartement::with(['immeuble', 'locataire', 'loyers']);
        // Ajouter un sous-select pour compter les factures impayées (statut non_paye ou partielle)
        $query->select('appartements.*');
        $query->selectSub(function($sub) {
            $sub->from('factures')
                ->join('loyers', 'factures.loyer_id', '=', 'loyers.id')
                ->whereColumn('loyers.appartement_id', 'appartements.id')
                ->whereIn('factures.statut_paiement', ['non_paye', 'partielle'])
                ->selectRaw('COUNT(*)');
        }, 'factures_impayees_count');
        if (request('immeuble')) {
            $query->whereHas('immeuble', function($q) {
                $q->where('nom', 'like', '%' . request('immeuble') . '%');
            });
        }
        if (request('numero')) {
            $query->where('numero', 'like', '%' . request('numero') . '%');
        }
        if (request('statut')) {
            $statut = request('statut');

            // Définition d'un loyer actif : statut = 'actif' AND date_debut <= today AND (date_fin IS NULL OR date_fin >= today)
            $activeLeaseConstraint = function ($q) {
                $q->where('statut', 'actif')
                  ->where('date_debut', '<=', now())
                  ->where(function ($sub) {
                      $sub->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', now());
                  });
            };

            if ($statut === 'libre') {
                // Libre : soit pas de loyer du tout, soit il y a des loyers mais aucun loyer actif
                $query->where(function($q) use ($activeLeaseConstraint) {
                    $q->whereDoesntHave('loyers')
                      ->orWhere(function($q2) use ($activeLeaseConstraint) {
                          $q2->whereDoesntHave('loyers', $activeLeaseConstraint);
                      });
                });
            } elseif ($statut === 'occupe') {
                // Occupé : loyer actif ET (date_fin IS NULL OR date_fin > now + 3 mois)
                $query->whereHas('loyers', function ($q) {
                    $q->where('statut', 'actif')
                      ->where('date_debut', '<=', now())
                      ->where(function ($sub) {
                          $sub->whereNull('date_fin')
                              ->orWhere('date_fin', '>', now()->addMonths(3));
                      });
                });
            } elseif ($statut === 'preavis') {
                // Préavis : loyer actif ET date_fin NOT NULL ET date_fin between today and now+3 months
                                $query->whereHas('loyers', function ($q) {
                                        $q->where('statut', 'actif')
                                            ->where('date_debut', '<=', now())
                                            ->whereNotNull('date_fin')
                                            ->where('date_fin', '>=', now())
                                            ->where('date_fin', '<=', now()->addMonths(3));
                                });
            }
        }
        $sort = request('sort');
        $direction = request('direction') === 'asc' ? 'asc' : 'desc';
        if ($sort === 'factures_impayees') {
            $query->orderBy('factures_impayees_count', $direction);
        } else {
            // tri par défaut : ordre d'enregistrement en base (id asc)
            $query->orderBy('id', 'asc');
        }

        $appartements = $query->paginate(10);
        // Conserver les paramètres de requête dans la pagination
        $appartements->appends(request()->query());

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
            'type' => 'nullable|in:local,1_pièce,2_pièces,3_pièces,4_pièces_plus,duplex',
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
            'type' => 'nullable|in:local,1_pièce,2_pièces,3_pièces,4_pièces_plus,duplex',
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