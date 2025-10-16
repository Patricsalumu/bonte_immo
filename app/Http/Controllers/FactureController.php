<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Facture;
use App\Models\Loyer;
use App\Models\Locataire;
use App\Models\Immeuble;
use App\Models\Paiement;
use App\Models\CompteFinancier;
use App\Models\MouvementCaisse;
use Carbon\Carbon;

class FactureController extends Controller
{
    /**
     * Exporter une facture en PDF (public, sans authentification)
     */
    public function exportPdfPublic(Facture $facture)
    {
        $facture->load(['locataire', 'loyer.appartement.immeuble']);
        $data = [
            'facture' => $facture,
            'entreprise' => [
                'nom' => 'La Bonte Immo',
                'adresse' => 'Avenue de la révolution, Q. Industriel C. Lshi',
                'telephone' => '+243 000 000 000',
                'email' => 'contact@labonteimmo.cd'
            ],
            'date_generation' => now()->format('d/m/Y H:i')
        ];
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('factures.pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $filename = "facture_{$facture->numero_facture}.pdf";
        return $pdf->download($filename);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Facture::with(['locataire', 'loyer.appartement.immeuble']);

        // Filtres
        if ($request->filled('mois')) {
            $query->where('mois', $request->mois);
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('statut')) {
            $query->where('statut_paiement', $request->statut);
        }

        if ($request->filled('immeuble_id')) {
            $query->whereHas('loyer.appartement', function($q) use ($request) {
                $q->where('immeuble_id', $request->immeuble_id);
            });
        }

        if ($request->filled('locataire_id')) {
            $query->where('locataire_id', $request->locataire_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhereHas('locataire', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
    // Par défaut, afficher les factures les plus récemment générées en premier
    $sortField = $request->get('sort', 'created_at');
    $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $factures = $query->paginate(20)->withQueryString();

        // Statistiques corrigées
        $montantTotalFactures = Facture::sum('montant');
        $montantTotalPaye = Paiement::whereNotNull('facture_id')->where('est_annule', false)->sum('montant');
        $montantImpaye = $montantTotalFactures - $montantTotalPaye;
        $stats = [
            'total' => Facture::count(),
            'non_payees' => Facture::where('statut_paiement', 'non_paye')->count(),
            'en_retard' => Facture::enRetard()->count(),
            'payees' => Facture::payees()->count(),
            'montant_total' => $montantTotalFactures,
            'montant_paye' => $montantTotalPaye,
            'montant_impaye' => $montantImpaye
        ];

        // Data pour les filtres
        $immeubles = Immeuble::orderBy('nom')->get();
        $locataires = Locataire::orderBy('nom')->get();
        $annees = Facture::selectRaw('DISTINCT annee')->orderBy('annee', 'desc')->pluck('annee');

    // La vue principale des factures/paiements est dans resources/views/paiements/index.blade.php
    return view('paiements.index', compact('factures', 'stats', 'immeubles', 'locataires', 'annees'));
    }

    /**
     * AJAX endpoint pour récupérer les lignes de la table paginée selon les filtres
     */
    public function ajaxList(Request $request)
    {
        $query = Facture::with(['locataire', 'loyer.appartement.immeuble']);

        // Réutiliser les mêmes filtres que dans index
        if ($request->filled('mois')) {
            $query->where('mois', $request->mois);
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('statut')) {
            $query->where('statut_paiement', $request->statut);
        }

        if ($request->filled('immeuble_id')) {
            $query->whereHas('loyer.appartement', function($q) use ($request) {
                $q->where('immeuble_id', $request->immeuble_id);
            });
        }

        if ($request->filled('locataire_id')) {
            $query->where('locataire_id', $request->locataire_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhereHas('locataire', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

    // Par défaut, afficher les factures les plus récemment générées en premier
    $sortField = $request->get('sort', 'created_at');
    $sortDirection = $request->get('direction', 'desc');
    $query->orderBy($sortField, $sortDirection);

        $perPage = intval($request->get('per_page', 20));
        $factures = $query->paginate($perPage)->appends($request->query());

        // Calculer les mêmes stats que la page
        $montantTotalFactures = Facture::sum('montant');
        $montantTotalPaye = Paiement::whereNotNull('facture_id')->where('est_annule', false)->sum('montant');
        $montantImpaye = $montantTotalFactures - $montantTotalPaye;
        $stats = [
            'total' => Facture::count(),
            'non_payees' => Facture::where('statut_paiement', 'non_paye')->count(),
            'en_retard' => Facture::enRetard()->count(),
            'payees' => Facture::payees()->count(),
            'montant_total' => $montantTotalFactures,
            'montant_paye' => $montantTotalPaye,
            'montant_impaye' => $montantImpaye
        ];

        // Rendre le partial des lignes
        $html = view('factures._table_rows', compact('factures'))->render();

        return response()->json([
            'html' => $html,
            'stats' => $stats,
            'pagination' => (string) $factures->links('vendor.pagination.custom')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loyers = Loyer::with(['locataire', 'appartement.immeuble'])
                      ->where('statut', 'actif')
                      ->get();

        return view('factures.create', compact('loyers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loyer_id' => 'required|exists:loyers,id',
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030',
            'montant' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Vérifier si une facture existe déjà
        $existante = Facture::where('loyer_id', $request->loyer_id)
                           ->where('mois', $request->mois)
                           ->where('annee', $request->annee)
                           ->first();

        if ($existante) {
            return back()->withErrors(['error' => 'Une facture existe déjà pour cette période.']);
        }

        // Après création de la facture, envoyer le message WhatsApp personnalisé au locataire
        // (exemple pour une facture non payée)
        // ... Création de la facture ...
        // Supposons $facture est la nouvelle facture créée
        // Personnalisation du message WhatsApp
        // (À placer après la création effective de la facture)
        /*
        $pdfUrl = url("public/factures/{$facture->id}/pdf");
        $messageWhatsApp = "Bonjour Mr/Mme {$facture->locataire->nom},\n\n";
        $messageWhatsApp .= "Votre facture de loyer n°{$facture->numero_facture} pour la période {$facture->getMoisNom()} {$facture->annee} a été générée.\n\n";
        $messageWhatsApp .= "Montant à payer : " . number_format($facture->montant, 0, ',', ' ') . " $\n";
        $messageWhatsApp .= "Date d'échéance : {$facture->date_echeance->format('d/m/Y')}\n\n";
        $messageWhatsApp .= "Vous pouvez télécharger votre facture à tout moment sur le lien suivant :\n{$pdfUrl}\n\n";
        $messageWhatsApp .= "Merci de procéder au règlement avant la date d'échéance.\n\n";
        $messageWhatsApp .= "Cordialement,\nL'équipe La Bonte Immo";
        // ... Générer le lien WhatsApp et l'envoyer au locataire ...
        */

        $loyer = Loyer::findOrFail($request->loyer_id);

        $facture = Facture::create([
            'loyer_id' => $request->loyer_id,
            'locataire_id' => $loyer->locataire_id,
            'mois' => $request->mois,
            'annee' => $request->annee,
            'montant' => $request->montant,
            'date_echeance' => $request->date_echeance,
            'notes' => $request->notes
        ]);

        return redirect()->route('factures.show', $facture)
                        ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facture $facture)
    {
        $facture->load(['locataire', 'loyer.appartement.immeuble', 'paiements.compte']);

        return view('factures.show', compact('facture'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facture $facture)
    {
        if ($facture->est_payee) {
            return back()->withErrors(['error' => 'Impossible de modifier une facture payée.']);
        }

        $loyers = Loyer::with(['locataire', 'appartement.immeuble'])
                      ->where('statut', 'actif')
                      ->get();

        return view('factures.edit', compact('facture', 'loyers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facture $facture)
    {
        if ($facture->est_payee) {
            return back()->withErrors(['error' => 'Impossible de modifier une facture payée.']);
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $facture->update($request->only(['montant', 'date_echeance', 'notes']));

        return redirect()->route('factures.show', $facture)
                        ->with('success', 'Facture modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facture $facture)
    {
        \Log::info('Tentative suppression facture', [
            'facture_id' => $facture->id,
            'numero_facture' => $facture->numero_facture,
            'statut_paiement' => $facture->statut_paiement,
            'montant_paye' => $facture->montant_paye,
            'user_id' => auth()->id(),
        ]);
        DB::transaction(function () use ($facture) {
            \Illuminate\Support\Facades\Log::info('Suppression facture en cours', [
                'facture_id' => $facture->id,
                'numero_facture' => $facture->numero_facture,
            ]);
            // 1. Déduire du compte financier et ajuster garantie locative
            foreach ($facture->paiements as $paiement) {
                $montant = $paiement->montant;
                $mode = $paiement->mode_paiement;
                $compte = $paiement->compte;
                // Fallback : si le paiement n'a pas de compte, utiliser le compte de l'utilisateur
                if (!$compte && $paiement->utilisateur && $paiement->utilisateur->compteFinancier) {
                    $compte = $paiement->utilisateur->compteFinancier;
                    \Illuminate\Support\Facades\Log::info('Fallback compte financier utilisateur', [
                        'paiement_id' => $paiement->id,
                        'user_id' => $paiement->utilisateur->id,
                        'compte_id' => $compte->id,
                    ]);
                }
                $loyer = $paiement->loyer;

                // Déduire le montant du compte financier
                if ($compte) {
                    \Illuminate\Support\Facades\Log::info('Déduction du compte financier', [
                        'compte_id' => $compte->id,
                        'montant' => $montant,
                    ]);
                    $compte->debiter($montant);
                } else {
                    \Illuminate\Support\Facades\Log::error('Aucun compte financier trouvé pour débiter', [
                        'paiement_id' => $paiement->id,
                        'user_id' => $paiement->utilisateur ? $paiement->utilisateur->id : null,
                    ]);
                }

                // Si paiement par garantie locative, réajuster la garantie
                if ($mode === 'garantie_locative' && $loyer) {
                    \Illuminate\Support\Facades\Log::info('Réajustement garantie locative', [
                        'loyer_id' => $loyer->id,
                        'montant_ajoute' => $montant,
                    ]);
                    $loyer->garantie_locative += $montant;
                    $loyer->save();
                }
            }

            // 2. Supprimer les mouvements liés à la facture
            foreach ($facture->paiements as $paiement) {
                $montant = $paiement->montant;
                $desc = 'Paiement facture ' . $facture->numero_facture;
                $mouvement = \App\Models\MouvementCaisse::where('description', 'like', "%$desc%")
                    ->where('montant', $montant)
                    ->first();
                if ($mouvement) {
                    \Illuminate\Support\Facades\Log::info('Suppression mouvement caisse', [
                        'mouvement_id' => $mouvement->id,
                        'description' => $mouvement->description,
                    ]);
                    $mouvement->delete();
                }
            }

            // 3. Supprimer les paiements liés à la facture
            foreach ($facture->paiements as $paiement) {
                \Illuminate\Support\Facades\Log::info('Suppression paiement', [
                    'paiement_id' => $paiement->id,
                    'montant' => $paiement->montant,
                    'mode' => $paiement->mode_paiement,
                ]);
                $paiement->delete();
            }

            // 4. Supprimer la facture
            $facture->delete();
            \Illuminate\Support\Facades\Log::info('Facture supprimée', [
                'facture_id' => $facture->id,
                'numero_facture' => $facture->numero_facture,
            ]);
        });

        return redirect()->route('paiements.index')
                        ->with('success', 'Facture et paiements supprimés avec succès.');
    }

    /**
     * Marquer une facture comme payée
     */
    public function marquerPayee(Request $request, Facture $facture)
    {
        // Log pour débogage
        Log::info('marquerPayee appelée', [
            'facture_id' => $facture->id,
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01|max:' . ($facture->montant - $facture->montantPaye()),
            'mode_paiement' => 'required|in:cash,virement,mobile_money,garantie_locative',
            'reference' => 'nullable|string|max:255',
        ]);

        // Vérifier le compte à débiter
        $compteId = auth()->user()->compte_financier_id ?? null;
        if (empty($compteId)) {
            return redirect()->back()->withErrors([
                'error' => "Aucun compte à débiter n'est configuré pour l'utilisateur. Veuillez d'abord configurer un compte dans le profil utilisateur."
            ]);
        }

        Log::info('Validation réussie', ['validated' => $validated]);

        try {
            DB::beginTransaction();

            $montantPaye = $validated['montant'];
            $montantRestant = $facture->montant - $facture->montantPaye();
            
            // Logique spéciale pour la garantie locative
            if ($validated['mode_paiement'] === 'garantie_locative') {
                $loyer = $facture->loyer;
                
                Log::info('Paiement par garantie locative', [
                    'garantie_disponible' => $loyer->garantie_locative,
                    'montant_demande' => $montantPaye,
                    'montant_facture' => $facture->montant
                ]);
                
                // Vérifier si la garantie locative est suffisante
                if ($loyer->garantie_locative < $montantPaye) {
                    DB::rollback();
                    return redirect()->back()->withErrors([
                        'error' => 'Garantie locative insuffisante. Disponible : ' . 
                                 number_format($loyer->garantie_locative, 0, ',', ' ') . ' $, ' .
                                 'Demandé : ' . number_format($montantPaye, 0, ',', ' ') . ' $'
                    ]);
                }
                
                // Déduire le montant de la garantie locative
                $loyer->update([
                    'garantie_locative' => $loyer->garantie_locative - $montantPaye
                ]);
                
                Log::info('Garantie locative mise à jour', [
                    'ancienne_garantie' => $loyer->garantie_locative + $montantPaye,
                    'nouvelle_garantie' => $loyer->garantie_locative,
                    'montant_deduit' => $montantPaye
                ]);
            }

            // Créer le paiement
            $notes = 'Paiement facture ' . $facture->numero_facture;
            if (!empty($validated['reference'])) {
                $notes .= ' - Référence: ' . $validated['reference'];
            }
            if ($validated['mode_paiement'] === 'garantie_locative') {
                $notes .= ' - Prélevé sur garantie locative';
            }
            
            $paiement = Paiement::create([
                'facture_id' => $facture->id,
                'loyer_id' => $facture->loyer_id,
                'locataire_id' => $facture->locataire_id,
                'montant' => $montantPaye,
                'date_paiement' => now(),
                'mode_paiement' => $validated['mode_paiement'],
                'reference_paiement' => $validated['reference'] ?? null,
                'utilisateur_id' => auth()->id(),
                'est_annule' => false,
                'notes' => $notes,
            ]);
            // Incrémenter le solde du compte configuré
            $compte = \App\Models\CompteFinancier::find($compteId);
            if ($compte) {
                $compte->increment('solde_actuel', $montantPaye);
            }

            // Déterminer le nouveau statut de la facture
            $montantActuelPaye = $facture->montantPaye();
            $nouveauMontantPaye = $montantActuelPaye + $montantPaye;
            
            Log::info('Calcul statut paiement', [
                'facture_id' => $facture->id,
                'montant_facture' => $facture->montant,
                'montant_actuel_paye' => $montantActuelPaye,
                'nouveau_paiement' => $montantPaye,
                'nouveau_total_paye' => $nouveauMontantPaye,
                'comparaison' => $nouveauMontantPaye >= $facture->montant ? 'COMPLET' : 'PARTIEL'
            ]);
            
            if ($nouveauMontantPaye == $facture->montant) {
                // Facture entièrement payée - vérifier si en retard
                $dateEcheance = Carbon::parse($facture->date_echeance);
                $aujourdhui = Carbon::now();
                if ($aujourdhui->gt($dateEcheance)) {
                    $nouveauStatut = 'paye_en_retard';
                } else {
                    $nouveauStatut = 'paye';
                }
            } elseif ($nouveauMontantPaye > 0) {
                $nouveauStatut = 'partielle';
            } else {
                $nouveauStatut = 'non_paye';
            }

            // Mettre à jour le statut de la facture
            $facture->update(['statut_paiement' => $nouveauStatut]);

            // Créer le mouvement financier (optionnel, si vous souhaitez garder une trace)
            MouvementCaisse::create([
                'compte_destination_id' => $compte->id,
                'type_mouvement' => 'entree',
                'montant' => $montantPaye,
                'mode_paiement' => $validated['mode_paiement'],
                'description' => 'Paiement facture ' . $facture->numero_facture . ' - ' . $facture->locataire->nom . ' ' . $facture->locataire->prenom,
                'categorie' => 'paiement_facture',
                'utilisateur_id' => auth()->id(),
                'date_operation' => now(),
                'est_annule' => false,
            ]);

            DB::commit();
            // Recalculer les montants et le statut après commit
            $facture->refresh();
            $montantTotalPaye = $facture->montantPaye();
            $resteAPayer = $facture->montant - $montantTotalPaye;

            // Correction : recalculer et mettre à jour le statut si besoin
            if ($montantTotalPaye == $facture->montant) {
                $dateEcheance = Carbon::parse($facture->date_echeance);
                $aujourdhui = Carbon::now();
                $nouveauStatut = $aujourdhui->gt($dateEcheance) ? 'paye_en_retard' : 'paye';
                $facture->update(['statut_paiement' => $nouveauStatut]);
            } elseif ($montantTotalPaye > 0) {
                $facture->update(['statut_paiement' => 'partielle']);
            } else {
                $facture->update(['statut_paiement' => 'non_paye']);
            }

            // Créer le message WhatsApp personnalisé
            $utilisateur = auth()->user();
            $dateFormatee = now()->format('d/m/Y');
            $moisFacture = $facture->mois . ' ' . $facture->annee;

            // Générer le lien public vers le PDF
            $pdfUrl = url("public/factures/{$facture->id}/pdf");

            $messageWhatsApp = "Bonjour Mr/Mme {$facture->locataire->nom},\n\n";
            $messageWhatsApp .= "Nous vous confirmons un paiement de " . number_format($montantPaye, 0, ',', ' ') . " $ ";
            $messageWhatsApp .= "qui a été enregistré le {$dateFormatee} par {$utilisateur->name} ";
            $messageWhatsApp .= "pour la facture n°{$facture->numero_facture} - {$moisFacture}.\n\n";
            $messageWhatsApp .= "Numéro facture : {$facture->numero_facture}\n";
            $messageWhatsApp .= "Reste à payer : " . number_format($resteAPayer, 0, ',', ' ') . " $\n\n";
            $messageWhatsApp .= "Vous pouvez télécharger votre facture à tout moment sur le lien suivant :\n{$pdfUrl}\n\n";

            if ($resteAPayer <= 0) {
                $messageWhatsApp .= "Votre facture a été complètement réglée. ✅\n\n";
            } else {
                $messageWhatsApp .= "Votre facture est partiellement réglée.\n\n";
            }

            $messageWhatsApp .= "Cordialement,\nL'équipe La Bonte Immo";

            // Encoder le message pour WhatsApp
            $messageEncoded = urlencode($messageWhatsApp);
            $numeroWhatsapp = $facture->locataire->telephone ?? '';
            $whatsappUrl = "https://wa.me/{$numeroWhatsapp}?text={$messageEncoded}";
            
            Log::info('Paiement créé avec succès', [
                'paiement_id' => $paiement->id,
                'nouveau_statut' => $nouveauStatut,
                'compte_id' => $compte->id,
                'reste_a_payer' => $resteAPayer
            ]);

            return redirect()->back()->with([
                'success' => 'Paiement de ' . number_format($montantPaye, 0, ',', ' ') . ' $ enregistré avec succès. ' .
                    ($resteAPayer <= 0 ? 'Facture entièrement payée' : 'Reste à payer : ' . number_format($resteAPayer, 0, ',', ' ') . ' $'),
                'whatsapp_url' => $whatsappUrl,
                'whatsapp_message' => $messageWhatsApp
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Erreur lors du paiement', [
                'facture_id' => $facture->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->withErrors([
                'error' => 'Erreur lors de l\'enregistrement du paiement : ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Générer les factures pour un mois donné
     */
    public function genererFacturesMois(Request $request)
    {
        $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030'
        ]);

        $mois = $request->mois;
        $annee = $request->annee;

        // Vérifier combien de factures existent déjà pour cette période
        $facturesExistantes = Facture::where('mois', $mois)
                                   ->where('annee', $annee)
                                   ->count();

        // Récupérer tous les loyers actifs
        $loyersActifs = Loyer::with(['locataire', 'appartement.immeuble'])
                            ->where('statut', 'actif')
                            ->count();

        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ][$mois];

        // Si toutes les factures existent déjà
        if ($facturesExistantes >= $loyersActifs && $loyersActifs > 0) {
            return back()->with('warning', 
                "Toutes les factures pour {$nomMois} {$annee} ont déjà été générées ({$facturesExistantes} factures)."
            );
        }

        try {
            $facturesCreees = Facture::genererFacturesPourMois($mois, $annee);

            $message = $facturesCreees > 0 
                ? "{$facturesCreees} nouvelle(s) facture(s) générée(s) pour {$nomMois} {$annee}"
                : "Aucune nouvelle facture à créer pour {$nomMois} {$annee}";

            $type = $facturesCreees > 0 ? 'success' : 'info';

            // Ajouter des détails sur les factures existantes si nécessaire
            if ($facturesExistantes > 0) {
                $message .= " ({$facturesExistantes} facture(s) existaient déjà)";
            }

            return back()->with($type, $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la génération des factures: ' . $e->getMessage()]);
        }
    }

    /**
     * Vérifier les doublons potentiels avant génération
     */
    public function verifierDoublons(Request $request)
    {
        $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030'
        ]);

        $mois = $request->mois;
        $annee = $request->annee;

        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ][$mois];

        // Compter les factures existantes
        $facturesExistantes = Facture::where('mois', $mois)
                                   ->where('annee', $annee)
                                   ->count();

        // Compter les loyers actifs
        $loyersActifs = Loyer::where('statut', 'actif')->count();

        // Facturer à créer
        $facturesACreer = max(0, $loyersActifs - $facturesExistantes);

        return response()->json([
            'periode' => "{$nomMois} {$annee}",
            'factures_existantes' => $facturesExistantes,
            'loyers_actifs' => $loyersActifs,
            'factures_a_creer' => $facturesACreer,
            'peut_generer' => $facturesACreer > 0
        ]);
    }

    /**
     * Exporter les factures
     */
    public function export(Request $request)
    {
        // À implémenter selon les besoins (PDF, Excel, etc.)
        // Pour l'instant, retourner simplement un JSON
        $factures = Facture::with(['locataire', 'loyer.appartement.immeuble'])->get();
        
        return response()->json($factures);
    }

    /**
     * Dashboard des factures avec statistiques
     */
    public function dashboard()
    {
        $moisCourant = now()->month;
        $anneeCourante = now()->year;

        $stats = [
            // Statistiques du mois courant
            'mois_courant' => [
                'total' => Facture::pourMois($moisCourant, $anneeCourante)->count(),
                'payees' => Facture::pourMois($moisCourant, $anneeCourante)->payees()->count(),
                'non_payees' => Facture::pourMois($moisCourant, $anneeCourante)->nonPayees()->count(),
                'montant_total' => Facture::pourMois($moisCourant, $anneeCourante)->sum('montant'),
                'montant_paye' => Facture::pourMois($moisCourant, $anneeCourante)->sum('montant_paye')
            ],
            
            // Factures en retard
            'en_retard' => [
                'count' => Facture::enRetard()->count(),
                'montant' => Facture::enRetard()->sum('montant')
            ],
            
            // Évolution sur 12 mois
            'evolution' => $this->getEvolutionFactures()
        ];

        return view('factures.dashboard', compact('stats'));
    }

    /**
     * Obtenir l'évolution des factures sur 12 mois
     */
    private function getEvolutionFactures()
    {
        $evolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois = $date->month;
            $annee = $date->year;
            
            $evolution[] = [
                'periode' => $date->format('M Y'),
                'total' => Facture::pourMois($mois, $annee)->count(),
                'payees' => Facture::pourMois($mois, $annee)->payees()->count(),
                'montant' => Facture::pourMois($mois, $annee)->sum('montant')
            ];
        }
        
        return $evolution;
    }

    /**
     * Exporter une facture en PDF
     */
    public function exportPdf(Facture $facture)
    {
        $facture->load(['locataire', 'loyer.appartement.immeuble']);
        
        // Données pour le PDF
        $data = [
            'facture' => $facture,
            'entreprise' => [
                'nom' => 'La Bonte Immo',
                'adresse' => 'Avenue de la révolution, Q. Industriel C. Lshi',
                'telephone' => '+243 000 000 000',
                'email' => 'contact@labonteimmo.cd'
            ],
            'date_generation' => now()->format('d/m/Y H:i')
        ];

        // Générer le PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('factures.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Nom du fichier
        $filename = "facture_{$facture->numero_facture}.pdf";

        return $pdf->download($filename);
    }
}
