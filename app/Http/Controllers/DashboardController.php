<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Locataire;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Facture;
use App\Models\Immeuble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalAppartements = Appartement::count();
        $appartementsOccupes = Appartement::where('statut', 'occupe')->count();
        $appartementsLibres = Appartement::where('statut', 'libre')->count();
        
        // Contrats de loyer actifs
        $contratsActifs = Loyer::actifs()->count();
        $contratsInactifs = Loyer::inactifs()->count();
        
        // Recettes du mois courant (basées sur les factures)
        $moisCourant = now()->month;
        $anneeCourante = now()->year;
        
        // Recettes du mois = somme des paiements enregistrés pendant le mois courant
        // Recettes du mois = somme des paiements enregistrés pendant le mois courant et liés à des factures
        $recettesMois = Paiement::whereNotNull('facture_id')
            ->whereMonth('date_paiement', $moisCourant)
            ->whereYear('date_paiement', $anneeCourante)
            ->where('est_annule', false)
            ->sum('montant');

    // Recettes totales : somme de tous les paiements valides liés à des factures (exclut garanties)
    $recettesTotales = Paiement::whereNotNull('facture_id')->where('est_annule', false)->sum('montant');
        
        // Factures non payées du mois (conserve si besoin)
        $facturesImpayees = Facture::where('mois', $moisCourant)
            ->where('annee', $anneeCourante)
            ->where('statut_paiement', '!=', 'paye')
            ->count();

        // --- Nouvelles métriques : factures impayées (toutes périodes) ---
        // Compter les factures dont le statut indique non payée ou partielle
        $facturesImpayeesCountAll = Facture::whereIn('statut_paiement', ['non_paye', 'partielle'])->count();

        // Calculer le montant total restant à payer pour ces factures (montant - paiements valides)
        // Optimisation : effectuer le calcul en SQL via une sous-requête qui somme les paiements non annulés par facture,
        // puis sommer les (montant - paiements) en évitant les chargements Eloquent en mémoire.
        $paidSub = DB::table('paiements')
            ->select('facture_id', DB::raw('SUM(montant) as paid'))
            ->where('est_annule', false)
            ->groupBy('facture_id');

        $facturesImpayeesAmountAll = (float) DB::table('factures as f')
            ->leftJoinSub($paidSub, 'p', function($join) {
                $join->on('p.facture_id', '=', 'f.id');
            })
            ->whereIn('f.statut_paiement', ['non_paye', 'partielle'])
            ->selectRaw('COALESCE(SUM(GREATEST(f.montant - COALESCE(p.paid, 0), 0)), 0) as total')
            ->value('total');
        
        // Paiements récents (7 derniers jours)
        $paiementsRecents = Paiement::with(['locataire', 'facture'])
            ->where('date_paiement', '>=', now()->subDays(7))
            ->where('est_annule', false)
            ->orderBy('date_paiement', 'desc')
            ->take(10)
            ->get();
        
        // Données pour graphiques - 12 derniers mois (basées sur les factures)
        $graphiqueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois = $date->month;
            $annee = $date->year;
            
            $facturesPayees = Facture::where('mois', $mois)
                ->where('annee', $annee)
                ->where('statut_paiement', '!=','non_paye')
                ->sum('montant');
                
            $facturesImpayees = Facture::where('mois', $mois)
                ->where('annee', $annee)
                ->where('statut_paiement',  'non_paye')
                ->sum('montant');
            
            $graphiqueData[] = [
                'mois' => $date->format('M Y'),
                'payes' => $facturesPayees,
                'impayes' => $facturesImpayees
            ];
        }
        
        // Garanties locatives par contrat actif
        $contratsAvecGarantie = Loyer::actifs()
            ->where('garantie_locative', '>', 0)
            ->with(['locataire', 'appartement'])
            ->get()
            ->map(function($loyer) {
                return [
                    'nom' => $loyer->locataire->nom . ' ' . $loyer->locataire->prenom,
                    'appartement' => $loyer->appartement->numero ?? 'N/A',
                    'garantie_initiale' => $loyer->garantie_locative,
                    'garantie_restante' => $loyer->garantie_locative // À ajuster selon la logique métier
                ];
            });
        
        return view('dashboard', compact(
            'totalAppartements',
            'appartementsOccupes', 
            'appartementsLibres',
            'contratsActifs',
            'contratsInactifs',
            'recettesMois',
            'recettesTotales',
            'facturesImpayees',
            'facturesImpayeesCountAll',
            'facturesImpayeesAmountAll',
            'paiementsRecents',
            'graphiqueData',
            'contratsAvecGarantie'
        ));
    }
}