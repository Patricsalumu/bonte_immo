<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Locataire;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Facture;
use App\Models\Immeuble;
use Illuminate\Http\Request;

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
        
        $recettesMois = Facture::where('mois', $moisCourant)
            ->where('annee', $anneeCourante)
            ->sum('montant_paye');
        
        // Factures non payées du mois
        $facturesImpayees = Facture::where('mois', $moisCourant)
            ->where('annee', $anneeCourante)
            ->where('statut_paiement', '!=', 'paye')
            ->count();
        
        // Paiements récents (7 derniers jours)
        $paiementsRecents = Paiement::with(['locataire', 'facture'])
            ->where('date_paiement', '>=', now()->subDays(7))
            ->where('est_annule', false)
            ->orderBy('date_paiement', 'desc')
            ->take(10)
            ->get();
        
        // Données pour graphiques - 6 derniers mois (basées sur les factures)
        $graphiqueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois = $date->month;
            $annee = $date->year;
            
            $facturesPayees = Facture::where('mois', $mois)
                ->where('annee', $annee)
                ->where('statut_paiement', 'paye')
                ->sum('montant');
                
            $facturesImpayees = Facture::where('mois', $mois)
                ->where('annee', $annee)
                ->where('statut_paiement', '!=', 'paye')
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
            'facturesImpayees',
            'paiementsRecents',
            'graphiqueData',
            'contratsAvecGarantie'
        ));
    }
}