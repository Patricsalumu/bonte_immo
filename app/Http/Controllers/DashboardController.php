<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Locataire;
use App\Models\Loyer;
use App\Models\Paiement;
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
        
        // Recettes du mois courant
        $moisCourant = now()->month;
        $anneeCourante = now()->year;
        
        $recettesMois = Paiement::whereHas('loyer', function($query) use ($moisCourant, $anneeCourante) {
            $query->where('mois', $moisCourant)
                  ->where('annee', $anneeCourante);
        })
        ->where('est_annule', false)
        ->sum('montant');
        
        // Factures non payées du mois
        $facturesImpayees = Loyer::where('mois', $moisCourant)
            ->where('annee', $anneeCourante)
            ->where('statut', '!=', 'paye')
            ->count();
        
        // Paiements récents (7 derniers jours)
        $paiementsRecents = Paiement::with(['locataire', 'loyer.appartement'])
            ->where('date_paiement', '>=', now()->subDays(7))
            ->where('est_annule', false)
            ->orderBy('date_paiement', 'desc')
            ->take(10)
            ->get();
        
        // Données pour graphiques - 6 derniers mois
        $graphiqueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois = $date->month;
            $annee = $date->year;
            
            $loyersPayes = Loyer::where('mois', $mois)
                ->where('annee', $annee)
                ->where('statut', 'paye')
                ->sum('montant');
                
            $loyersImpayes = Loyer::where('mois', $mois)
                ->where('annee', $annee)
                ->where('statut', '!=', 'paye')
                ->sum('montant');
            
            $graphiqueData[] = [
                'mois' => $date->format('M Y'),
                'payes' => $loyersPayes,
                'impayes' => $loyersImpayes
            ];
        }
        
        // Garanties locatives par locataire
        $locatairesAvecGarantie = Locataire::whereNotNull('date_entree')
            ->whereNull('date_sortie')
            ->where('garantie_initiale', '>', 0)
            ->with('appartement')
            ->get()
            ->map(function($locataire) {
                return [
                    'nom' => $locataire->nom,
                    'appartement' => $locataire->appartement->numero ?? 'N/A',
                    'garantie_initiale' => $locataire->garantie_initiale,
                    'garantie_restante' => $locataire->garantieRestante()
                ];
            });
        
        return view('dashboard', compact(
            'totalAppartements',
            'appartementsOccupes', 
            'appartementsLibres',
            'recettesMois',
            'facturesImpayees',
            'paiementsRecents',
            'graphiqueData',
            'locatairesAvecGarantie'
        ));
    }
}