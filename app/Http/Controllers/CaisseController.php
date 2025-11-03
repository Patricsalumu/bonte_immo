<?php

namespace App\Http\Controllers;

use App\Models\CompteFinancier;
use App\Models\MouvementCaisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaisseController extends Controller
{
    public function __construct()
    {
        $this->middleware('gestionnaire');
        $this->middleware('admin')->only(['create', 'store', 'transfert', 'executeTransfert', 'annuler']);
    }

    public function index()
    {
        $comptes = CompteFinancier::where('actif', true)->get();
                $soldeTotal = $comptes->sum('solde_actuel');
        
        $mouvementsRecents = MouvementCaisse::with(['compteSource', 'compteDestination', 'utilisateur'])
            ->where('est_annule', false)
            ->orderBy('date_operation', 'desc')
            ->take(10)
            ->get();

        return view('caisse.index', compact('comptes', 'soldeTotal', 'mouvementsRecents'));
    }

    public function journal(Request $request)
    {
        $query = MouvementCaisse::with(['compteSource', 'compteDestination', 'utilisateur'])
            ->where('est_annule', false);

        // Filtres
        if ($request->filled('compte_id')) {
            $query->where(function($q) use ($request) {
                $q->where('compte_source_id', $request->compte_id)
                  ->orWhere('compte_destination_id', $request->compte_id);
            });
        }

        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_operation', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_operation', '<=', $request->date_fin);
        }

        // Recherche libre (description, reference, montant) — n'inclure montant que si q est numérique
        if ($request->filled('q')) {
            $qTerm = $request->q;
            // Normaliser la chaîne pour tester si elle représente un nombre
            $qNumericCandidate = preg_replace('/[^0-9,\.\-]/', '', $qTerm);
            $qNumericCandidate = str_replace(' ', '', $qNumericCandidate);
            $isNumeric = is_numeric(str_replace(',', '.', $qNumericCandidate));

            $query->where(function($sub) use ($qTerm, $isNumeric, $qNumericCandidate) {
                $sub->where('description', 'like', '%' . $qTerm . '%');
                if ($isNumeric) {
                    // si numérique, rechercher aussi dans montant (CAST en CHAR)
                    $sub->orWhereRaw('CAST(montant AS CHAR) LIKE ?', ['%' . $qNumericCandidate . '%']);
                }
            });
        }

        $mouvements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Appliquer les mêmes filtres pour les statistiques
        $baseStatsQuery = MouvementCaisse::where('est_annule', false);
        if ($request->filled('compte_id')) {
            $baseStatsQuery = $baseStatsQuery->where(function($q) use ($request) {
                $q->where('compte_source_id', $request->compte_id)
                  ->orWhere('compte_destination_id', $request->compte_id);
            });
        }
        if ($request->filled('type_mouvement')) {
            $baseStatsQuery = $baseStatsQuery->where('type_mouvement', $request->type_mouvement);
        }
        if ($request->filled('date_debut')) {
            $baseStatsQuery = $baseStatsQuery->where('date_operation', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $baseStatsQuery = $baseStatsQuery->where('date_operation', '<=', $request->date_fin);
        }

        // Appliquer la même recherche libre aux statistiques si fournie
        if ($request->filled('q')) {
            $qTerm = $request->q;
            $qNumericCandidate = preg_replace('/[^0-9,\.\-]/', '', $qTerm);
            $qNumericCandidate = str_replace(' ', '', $qNumericCandidate);
            $isNumeric = is_numeric(str_replace(',', '.', $qNumericCandidate));

            $baseStatsQuery = $baseStatsQuery->where(function($sub) use ($qTerm, $isNumeric, $qNumericCandidate) {
                $sub->where('description', 'like', '%' . $qTerm . '%');
                if ($isNumeric) {
                    $sub->orWhereRaw('CAST(montant AS CHAR) LIKE ?', ['%' . $qNumericCandidate . '%']);
                }
            });
        }

        $statistiques = [
            'total_entrees' => (clone $baseStatsQuery)->where('type_mouvement', 'entree')->sum('montant'),
            'total_sorties' => (clone $baseStatsQuery)->where('type_mouvement', 'sortie')->sum('montant'),
            'total_transferts' => (clone $baseStatsQuery)->where('type_mouvement', 'transfert')->sum('montant'),
            'solde_net' => 0
        ];
        $statistiques['solde_net'] = $statistiques['total_entrees'] - $statistiques['total_sorties'];
        $comptes = CompteFinancier::where('actif', true)->get();

        // Calculer la répartition des entrées par période trouvée dans la description (ex: 10/2025 ou octobre 2025)
        $periodeStats = [];
        // Récupérer les mouvements de type 'entree' correspondant aux mêmes filtres
        $entries = (clone $baseStatsQuery)->where('type_mouvement', 'entree')->get();
        $monthsFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];
        foreach ($entries as $e) {
            $found = false;
            $desc = $e->description ?? '';
            // Chercher format MM/YYYY
            if (preg_match('/\b(0?[1-9]|1[0-2])\/(\d{4})\b/', $desc, $m)) {
                $mm = intval($m[1]); $yy = intval($m[2]);
                $key = sprintf('%04d-%02d', $yy, $mm);
                $label = $monthsFr[$mm] . ' ' . $yy;
                $periodeStats[$key]['label'] = $label;
                $periodeStats[$key]['amount'] = ($periodeStats[$key]['amount'] ?? 0) + $e->montant;
                $found = true;
            }
            // Chercher format mois en lettres + année (ex: octobre 2025)
            if (!$found && preg_match('/\b(janvier|fevrier|février|mars|avril|mai|juin|juillet|aout|août|septembre|octobre|novembre|decembre)\s+(\d{4})\b/i', $desc, $m2)) {
                $moisName = strtolower(str_replace(['é','è','ê','à','ù'], ['e','e','e','a','u'], $m2[1]));
                $moisMap = ['janvier'=>1,'fevrier'=>2,'fevrier'=>2,'mars'=>3,'avril'=>4,'mai'=>5,'juin'=>6,'juillet'=>7,'aout'=>8,'aôut'=>8,'aout'=>8,'septembre'=>9,'octobre'=>10,'novembre'=>11,'decembre'=>12];
                $yy = intval($m2[2]);
                $mm = $moisMap[$moisName] ?? null;
                if ($mm) {
                    $key = sprintf('%04d-%02d', $yy, $mm);
                    $label = $monthsFr[$mm] . ' ' . $yy;
                    $periodeStats[$key]['label'] = $label;
                    $periodeStats[$key]['amount'] = ($periodeStats[$key]['amount'] ?? 0) + $e->montant;
                    $found = true;
                }
            }
            if (!$found) {
                $periodeStats['unspecified']['label'] = 'Non spécifié';
                $periodeStats['unspecified']['amount'] = ($periodeStats['unspecified']['amount'] ?? 0) + $e->montant;
            }
        }
        // Trier par clé (période décroissante)
        uksort($periodeStats, function($a, $b) {
            if ($a === 'unspecified') return 1;
            if ($b === 'unspecified') return -1;
            return strcmp($b, $a);
        });

        return view('caisse.journal', compact('mouvements', 'comptes', 'statistiques', 'periodeStats'));
    }

    /**
     * Exporter le journal de caisse en PDF selon les mêmes filtres/recherche
     */
    public function exportPdf(Request $request)
    {
        // Reprendre la même construction de query que dans journal()
        $query = MouvementCaisse::with(['compteSource', 'compteDestination', 'utilisateur'])
            ->where('est_annule', false);

        if ($request->filled('compte_id')) {
            $query->where(function($q) use ($request) {
                $q->where('compte_source_id', $request->compte_id)
                  ->orWhere('compte_destination_id', $request->compte_id);
            });
        }

        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_operation', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_operation', '<=', $request->date_fin);
        }

        if ($request->filled('q')) {
            $qTerm = $request->q;
            $query->where(function($sub) use ($qTerm) {
                $sub->where('description', 'like', '%' . $qTerm . '%')
                    ->orWhereRaw('CAST(montant AS CHAR) LIKE ?', ['%' . $qTerm . '%']);
            });
        }

        $mouvements = $query->orderBy('created_at', 'desc')->get();

        // Calculer les statistiques (mêmes règles que dans journal)
        $baseStatsQuery = MouvementCaisse::where('est_annule', false);
        if ($request->filled('compte_id')) {
            $baseStatsQuery = $baseStatsQuery->where(function($q) use ($request) {
                $q->where('compte_source_id', $request->compte_id)
                  ->orWhere('compte_destination_id', $request->compte_id);
            });
        }
        if ($request->filled('type_mouvement')) {
            $baseStatsQuery = $baseStatsQuery->where('type_mouvement', $request->type_mouvement);
        }
        if ($request->filled('date_debut')) {
            $baseStatsQuery = $baseStatsQuery->where('date_operation', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $baseStatsQuery = $baseStatsQuery->where('date_operation', '<=', $request->date_fin);
        }
        if ($request->filled('q')) {
            $qTerm = $request->q;
            $baseStatsQuery = $baseStatsQuery->where(function($sub) use ($qTerm) {
                $sub->where('description', 'like', '%' . $qTerm . '%')
                    ->orWhereRaw('CAST(montant AS CHAR) LIKE ?', ['%' . $qTerm . '%']);
            });
        }

        $statistiques = [
            'total_entrees' => (clone $baseStatsQuery)->where('type_mouvement', 'entree')->sum('montant'),
            'total_sorties' => (clone $baseStatsQuery)->where('type_mouvement', 'sortie')->sum('montant'),
            'total_transferts' => (clone $baseStatsQuery)->where('type_mouvement', 'transfert')->sum('montant'),
            'solde_net' => 0
        ];
        $statistiques['solde_net'] = $statistiques['total_entrees'] - $statistiques['total_sorties'];

        // Générer le PDF via Dompdf
        // Calculer la répartition des entrées par période pour le PDF aussi
        $periodeStats = [];
        $entriesPdf = (clone $baseStatsQuery)->where('type_mouvement', 'entree')->get();
        $monthsFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];
        foreach ($entriesPdf as $e) {
            $found = false;
            $desc = $e->description ?? '';
            if (preg_match('/\b(0?[1-9]|1[0-2])\/(\d{4})\b/', $desc, $m)) {
                $mm = intval($m[1]); $yy = intval($m[2]);
                $key = sprintf('%04d-%02d', $yy, $mm);
                $label = $monthsFr[$mm] . ' ' . $yy;
                $periodeStats[$key]['label'] = $label;
                $periodeStats[$key]['amount'] = ($periodeStats[$key]['amount'] ?? 0) + $e->montant;
                $found = true;
            }
            if (!$found && preg_match('/\b(janvier|fevrier|février|mars|avril|mai|juin|juillet|aout|août|septembre|octobre|novembre|decembre)\s+(\d{4})\b/i', $desc, $m2)) {
                $moisName = strtolower(str_replace(['é','è','ê','à','ù'], ['e','e','e','a','u'], $m2[1]));
                $moisMap = ['janvier'=>1,'fevrier'=>2,'fevrier'=>2,'mars'=>3,'avril'=>4,'mai'=>5,'juin'=>6,'juillet'=>7,'aout'=>8,'aôut'=>8,'aout'=>8,'septembre'=>9,'octobre'=>10,'novembre'=>11,'decembre'=>12];
                $yy = intval($m2[2]);
                $mm = $moisMap[$moisName] ?? null;
                if ($mm) {
                    $key = sprintf('%04d-%02d', $yy, $mm);
                    $label = $monthsFr[$mm] . ' ' . $yy;
                    $periodeStats[$key]['label'] = $label;
                    $periodeStats[$key]['amount'] = ($periodeStats[$key]['amount'] ?? 0) + $e->montant;
                    $found = true;
                }
            }
            if (!$found) {
                $periodeStats['unspecified']['label'] = 'Non spécifié';
                $periodeStats['unspecified']['amount'] = ($periodeStats['unspecified']['amount'] ?? 0) + $e->montant;
            }
        }
        uksort($periodeStats, function($a, $b) {
            if ($a === 'unspecified') return 1;
            if ($b === 'unspecified') return -1;
            return strcmp($b, $a);
        });

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('caisse.journal_pdf', compact('mouvements', 'statistiques', 'request', 'periodeStats'));

        $filename = 'journal_caisse_' . now()->format('Y_m_d') . '.pdf';
        return $pdf->download($filename);
    }

    public function create()
    {
        $comptes = CompteFinancier::all();
        return view('caisse.create', compact('comptes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_mouvement' => 'required|in:entree,sortie',
            'compte_id' => 'required|exists:comptes_financiers,id',
            'montant' => 'required|numeric|min:0.01',
            'mode_paiement' => 'nullable|string',
            'description' => 'required|string|max:1000',
            'categorie' => 'nullable|string|max:255',
            'date_operation' => 'required|date',
        ]);

        $compte = CompteFinancier::findOrFail($validated['compte_id']);

        // Vérifier si le compte a suffisamment de fonds pour une sortie
        if ($validated['type_mouvement'] === 'sortie' && $compte->solde_actuel < $validated['montant']) {
            return back()->with('error', 'Solde insuffisant dans le compte sélectionné.');
        }

        $mouvement = MouvementCaisse::create([
            'compte_source_id' => $validated['type_mouvement'] === 'sortie' ? $validated['compte_id'] : null,
            'compte_destination_id' => $validated['type_mouvement'] === 'entree' ? $validated['compte_id'] : null,
            'type_mouvement' => $validated['type_mouvement'],
            'montant' => $validated['montant'],
            'mode_paiement' => $validated['mode_paiement'],
            'description' => $validated['description'],
            'categorie' => $validated['categorie'],
            'utilisateur_id' => auth()->id(),
            'date_operation' => $validated['date_operation'],
        ]);

        // Mettre à jour le solde du compte
        if ($validated['type_mouvement'] === 'entree') {
            $compte->crediter($validated['montant']);
        } else {
            $compte->debiter($validated['montant']);
        }

        return redirect()->route('caisse.index')
            ->with('success', 'Mouvement enregistré avec succès.');
    }

    public function transfert()
    {
        $comptes = CompteFinancier::all();
        return view('caisse.transfert', compact('comptes'));
    }

    public function executeTransfert(Request $request)
    {
    Log::info('Tentative de transfert', [
            'input' => $request->all()
        ]);
        try {
            $validated = $request->validate([
                'compte_source_id' => 'required|exists:comptes_financiers,id',
                'compte_destination_id' => 'required|exists:comptes_financiers,id|different:compte_source_id',
                'montant' => 'required|numeric|min:0.01',
                'description' => 'required|string|max:1000',
                'date_operation' => 'required|date',
            ]);
            Log::info('Validation OK', $validated);
            $compteSource = CompteFinancier::findOrFail($validated['compte_source_id']);
            $compteDestination = CompteFinancier::findOrFail($validated['compte_destination_id']);
            Log::info('Comptes trouvés', [
                'source' => $compteSource->toArray(),
                'destination' => $compteDestination->toArray()
            ]);
            // Vérifier si le compte source a suffisamment de fonds
            if ($compteSource->solde_actuel < $validated['montant']) {
                Log::warning('Solde insuffisant', [
                    'solde' => $compteSource->solde_actuel,
                    'montant' => $validated['montant']
                ]);
                return back()->withInput()->with('error', 'Solde insuffisant dans le compte source. Le montant demandé (' . number_format($validated['montant'], 2, ',', ' ') . ' $) dépasse le solde disponible (' . number_format($compteSource->solde_actuel, 2, ',', ' ') . ' $).');
            }
            // Créer mouvement sortie (compte source)
            $mouvementSortie = MouvementCaisse::create([
                'compte_source_id' => $compteSource->id,
                'compte_destination_id' => null,
                'type_mouvement' => 'sortie',
                'montant' => $validated['montant'],
                'mode_paiement' => 'transfert',
                'description' => $validated['description'],
                'categorie' => 'Transfert',
                'utilisateur_id' => auth()->id(),
                'date_operation' => $validated['date_operation'],
            ]);
            $compteSource->debiter($validated['montant']);

            // Créer mouvement entrée (compte destination)
            $mouvementEntree = MouvementCaisse::create([
                'compte_source_id' => null,
                'compte_destination_id' => $compteDestination->id,
                'type_mouvement' => 'entree',
                'montant' => $validated['montant'],
                'mode_paiement' => 'transfert',
                'description' => $validated['description'],
                'categorie' => 'Transfert',
                'utilisateur_id' => auth()->id(),
                'date_operation' => $validated['date_operation'],
            ]);
            $compteDestination->crediter($validated['montant']);

            Log::info('Transfert effectué (double écriture)', [
                'source_id' => $compteSource->id,
                'destination_id' => $compteDestination->id,
                'montant' => $validated['montant']
            ]);
            return redirect()->route('caisse.journal')
                ->with('success', 'Transfert effectué avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur transfert caisse', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Erreur lors du transfert : ' . $e->getMessage());
        }
    }

    public function annuler(MouvementCaisse $mouvement)
    {
        if ($mouvement->est_annule) {
            return back()->with('error', 'Ce mouvement est déjà annulé.');
        }

        $mouvement->annuler();

        return back()->with('success', 'Mouvement annulé avec succès.');
    }
}