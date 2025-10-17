<?php

namespace App\Http\Controllers;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\CompteFinancier;
use App\Models\Appartement;
use App\Models\Locataire;
use App\Models\Facture;
use Illuminate\Http\Request;
use PDF;
use Excel;

class RapportController extends Controller
{
    public function __construct()
    {
        // Require authentication for all methods, but only apply the admin
        // middleware to admin-only actions. Leave `mensuel`, `export` and
        // `facturePdf` accessible to any authenticated user (they were
        // requested to be available to gestionnaires / users).
        $this->middleware('admin')->except(['mensuel', 'export', 'facturePdf']);
        $this->middleware('auth');
    }

    public function index()
    {
        // Redirige vers le dashboard des factures par défaut
        return redirect()->route('factures.dashboard');
    }

    public function mensuel(Request $request)
    {
            $nomMois = [
                1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
            ];

            $mois = $request->input('mois', now()->month);
            $annee = $request->input('annee', now()->year);
            $percepteurId = $request->input('percepteur');
            $statut = $request->input('statut');

            // Liste des percepteurs pour le filtre
            $percepteurs = \App\Models\User::where('role', 'gestionnaire')->orWhere('role', 'admin')->get();
            // Calcul du montant encaissé par percepteur pour la période
            foreach ($percepteurs as $percepteur) {
                $percepteur->total_encaisse = Paiement::where('utilisateur_id', $percepteur->id)
                    ->where('est_annule', false)
                    ->whereHas('facture', function($q) use ($mois, $annee) {
                        $q->where('mois', $mois)->where('annee', $annee);
                    })
                    ->sum('montant');
            }

            // Récupère les paiements valides du mois/année
            $paiementsQuery = Paiement::with(['facture', 'locataire', 'utilisateur'])
                ->whereHas('facture', function($q) use ($mois, $annee) {
                    $q->where('mois', $mois)->where('annee', $annee);
                })
                ->where('est_annule', false);

            if ($percepteurId) {
                $paiementsQuery->where('utilisateur_id', $percepteurId);
            }

            // Filtre par statut de paiement
            if ($statut === 'payee') {
                $paiementsQuery->whereHas('facture', function($q) {
                    $q->whereIn('statut_paiement', ['paye', 'paye_en_retard']);
                });
            } elseif ($statut === 'non_payee') {
                $paiementsQuery->whereHas('facture', function($q) {
                    $q->where('statut_paiement', 'non_paye');
                });
            } elseif ($statut === 'partielle') {
                $paiementsQuery->whereHas('facture', function($q) {
                    $q->where('statut_paiement', 'partielle');
                });
            }

            $paiementsFiltres = $paiementsQuery->get();

            // Récupère les factures du mois/année
            $facturesQuery = Facture::with(['appartement.immeuble', 'locataire', 'paiements.utilisateur'])
                ->where('mois', $mois)
                ->where('annee', $annee);

            // Si filtre percepteur, ne garder que les factures ayant au moins un paiement du percepteur
            if ($percepteurId) {
                $facturesQuery->whereHas('paiements', function($q) use ($percepteurId) {
                    $q->where('utilisateur_id', $percepteurId)->where('est_annule', false);
                });
            }

            // Filtre par statut de paiement
            if ($statut === 'payee') {
                $facturesQuery->whereIn('statut_paiement', ['paye', 'paye_en_retard']);
            } elseif ($statut === 'non_payee') {
                $facturesQuery->where('statut_paiement', 'non_paye');
            } elseif ($statut === 'partielle') {
                $facturesQuery->where('statut_paiement', 'partielle');
            }

            $factures = $facturesQuery->get();

            // Si un filtre de statut est demandé, filtrer la collection en mémoire
            // en se basant sur la somme des paiements valides pour éviter les cas
            // où le champ `statut_paiement` n'a pas été mis à jour correctement.
            if ($statut) {
                if ($statut === 'payee') {
                    $factures = $factures->filter(function($f) {
                        $somme = $f->paiements->where('est_annule', false)->sum('montant');
                        return $somme >= $f->montant;
                    })->values();
                } elseif ($statut === 'non_payee') {
                    $factures = $factures->filter(function($f) {
                        $somme = $f->paiements->where('est_annule', false)->sum('montant');
                        return $somme == 0;
                    })->values();
                } elseif ($statut === 'partielle') {
                    $factures = $factures->filter(function($f) {
                        $somme = $f->paiements->where('est_annule', false)->sum('montant');
                        return $somme > 0 && $somme < $f->montant;
                    })->values();
                }
            }

            // Calcul du total payé chez le percepteur sélectionné
            $totalPayesPercepteur = $paiementsFiltres->sum('montant');

            // Statistiques globales
            $totalFactures = $factures->sum('montant');
            $totalPayes = $factures->reduce(function($carry, $facture) {
                return $carry + $facture->paiements->where('est_annule', false)->sum('montant');
            }, 0);
            $resteAPayer = $totalFactures - $totalPayes;

            return view('rapports.mensuel', compact(
                'factures', 'mois', 'annee', 'nomMois', 'percepteurs', 'totalPayesPercepteur', 'totalFactures', 'totalPayes', 'resteAPayer'
            ));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'mensuel');
        $format = $request->input('format', 'pdf');
        $mois = $request->input('mois', now()->month);
        $annee = $request->input('annee', now()->year);

        switch ($type) {
            case 'mensuel':
                return $this->exportMensuel($format, $mois, $annee);
            case 'factures_impayees':
                return $this->exportFacturesImpayees($format);
            case 'tresorerie':
                return $this->exportTresorerie($format);
            default:
                return back()->with('error', 'Type de rapport non valide.');
        }
    }

    private function exportMensuel($format, $mois, $annee)
        {
            $request = request();
            $percepteurId = $request->input('percepteur');
            $statut = $request->input('statut');

            $nomMois = [
                1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
            ];

            $percepteurs = \App\Models\User::where('role', 'gestionnaire')->orWhere('role', 'admin')->get();

            // Récupère les paiements valides du mois/année
            $paiementsQuery = Paiement::with(['facture', 'locataire', 'utilisateur'])
                ->whereHas('facture', function($q) use ($mois, $annee) {
                    $q->where('mois', $mois)->where('annee', $annee);
                })
                ->where('est_annule', false);

            if ($percepteurId) {
                $paiementsQuery->where('utilisateur_id', $percepteurId);
            }

            if ($statut === 'payee') {
                // Inclure les factures payées et payées en retard
                $paiementsQuery->whereHas('facture', function($q) {
                    $q->whereIn('statut_paiement', ['paye', 'paye_en_retard']);
                });
            } elseif ($statut === 'non_payee') {
                $paiementsQuery->whereHas('facture', function($q) {
                    $q->where('statut_paiement', 'non_paye');
                });
            } elseif ($statut === 'partielle') {
                $paiementsQuery->whereHas('facture', function($q) {
                    $q->where('statut_paiement', 'partielle');
                });
            }

            $paiementsFiltres = $paiementsQuery->get();

            // Récupère les factures du mois/année
            $facturesQuery = Facture::with(['appartement.immeuble', 'locataire', 'paiements.utilisateur'])
                ->where('mois', $mois)
                ->where('annee', $annee);

            if ($percepteurId) {
                $facturesQuery->whereHas('paiements', function($q) use ($percepteurId) {
                    $q->where('utilisateur_id', $percepteurId)->where('est_annule', false);
                });
            }
            if ($statut === 'payee') {
                // Inclure les factures payées et payées en retard
                $facturesQuery->whereIn('statut_paiement', ['paye', 'paye_en_retard']);
            } elseif ($statut === 'non_payee') {
                $facturesQuery->where('statut_paiement', 'non_paye');
            } elseif ($statut === 'partielle') {
                $facturesQuery->where('statut_paiement', 'partielle');
            }

            $factures = $facturesQuery->get();

            // Même filtrage robuste pour l'export — filtrer sur les sommes de paiements
            if ($statut) {
                if ($statut === 'payee') {
                    $factures = $factures->filter(function($f) {
                        $somme = $f->paiements->where('est_annule', false)->sum('montant');
                        return $somme >= $f->montant;
                    })->values();
                } elseif ($statut === 'non_payee') {
                    $factures = $factures->filter(function($f) {
                        $somme = $f->paiements->where('est_annule', false)->sum('montant');
                        return $somme == 0;
                    })->values();
                } elseif ($statut === 'partielle') {
                    $factures = $factures->filter(function($f) {
                        $somme = $f->paiements->where('est_annule', false)->sum('montant');
                        return $somme > 0 && $somme < $f->montant;
                    })->values();
                }
            }

            // Calcul du montant encaissé par percepteur pour la période
            foreach ($percepteurs as $percepteur) {
                $percepteur->total_encaisse = Paiement::where('utilisateur_id', $percepteur->id)
                    ->where('est_annule', false)
                    ->whereHas('facture', function($q) use ($mois, $annee) {
                        $q->where('mois', $mois)->where('annee', $annee);
                    })
                    ->sum('montant');
            }

            // Calcul du total payé chez le percepteur sélectionné
            $totalPayesPercepteur = $paiementsFiltres->sum('montant');

            // Statistiques globales
            $totalFactures = $factures->sum('montant');
            $totalPayes = $factures->reduce(function($carry, $facture) {
                return $carry + $facture->paiements->where('est_annule', false)->sum('montant');
            }, 0);
            $resteAPayer = $totalFactures - $totalPayes;

            $data = compact(
                'factures', 'mois', 'annee', 'nomMois', 'percepteurs', 'totalPayesPercepteur', 'totalFactures', 'totalPayes', 'resteAPayer', 'statut', 'percepteurId'
            );

            if ($format === 'pdf') {
                $pdf = PDF::loadView('rapports.pdf.mensuel', $data);
                return $pdf->download("rapport_mensuel_{$mois}_{$annee}.pdf");
            } else {
                return Excel::download(new \App\Exports\RapportMensuelExport($data), "rapport_mensuel_{$mois}_{$annee}.xlsx");
            }
        }

    private function exportFacturesImpayees($format)
    {
        $facturesImpayees = Loyer::with(['appartement.immeuble', 'locataire'])
            ->where('statut', '!=', 'paye')
            ->orderBy('date_echeance', 'asc')
            ->get();

        $data = compact('facturesImpayees');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('rapports.pdf.factures_impayees', $data);
            return $pdf->download('factures_impayees_' . now()->format('Y_m_d') . '.pdf');
        } else {
            return Excel::download(new \App\Exports\FacturesImpayeesExport($data), 'factures_impayees_' . now()->format('Y_m_d') . '.xlsx');
        }
    }

    private function exportTresorerie($format)
    {
        $comptes = CompteFinancier::all();
        $soldeTotal = $comptes->sum('solde_actuel');

        // Mouvements du mois courant
        $mouvements = \App\Models\MouvementCaisse::with(['compteSource', 'compteDestination'])
            ->whereMonth('date_operation', now()->month)
            ->whereYear('date_operation', now()->year)
            ->where('est_annule', false)
            ->orderBy('date_operation', 'desc')
            ->get();

        $entrees = $mouvements->where('type_mouvement', 'entree')->sum('montant');
        $sorties = $mouvements->where('type_mouvement', 'sortie')->sum('montant');

        $data = compact('comptes', 'soldeTotal', 'mouvements', 'entrees', 'sorties');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('rapports.pdf.tresorerie', $data);
            return $pdf->download('rapport_tresorerie_' . now()->format('Y_m_d') . '.pdf');
        } else {
            return Excel::download(new \App\Exports\TresorerieExport($data), 'rapport_tresorerie_' . now()->format('Y_m_d') . '.xlsx');
        }
    }

    public function facturesImpayees()
    {
        $facturesImpayees = Loyer::with(['appartement.immeuble', 'locataire'])
            ->where('statut', '!=', 'paye')
            ->orderBy('date_echeance', 'asc')
            ->paginate(20);

        return view('rapports.factures_impayees', compact('facturesImpayees'));
    }

    public function facturePdf(Loyer $loyer)
    {
        $loyer->load(['appartement.immeuble', 'locataire', 'paiements']);
        
        $data = compact('loyer');
        
        $pdf = PDF::loadView('rapports.pdf.facture', $data);
        
        $nomFichier = "facture_{$loyer->locataire->nom}_{$loyer->mois}_{$loyer->annee}.pdf";
        
        return $pdf->download($nomFichier);
    }
}