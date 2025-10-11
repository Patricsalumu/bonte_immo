<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facture;
use Carbon\Carbon;

class GenererFacturesMensuelles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factures:generer 
                           {--mois= : Mois spécifique à facturer (1-12)}
                           {--annee= : Année spécifique à facturer}
                           {--force : Forcer la génération même si des factures existent déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère automatiquement les factures pour le mois précédent (système congolais)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏠 Génération des factures mensuelles...');
        
        // Déterminer le mois et l'année à facturer
        if ($this->option('mois') && $this->option('annee')) {
            // Mois et année spécifiés
            $mois = (int) $this->option('mois');
            $annee = (int) $this->option('annee');
            
            if ($mois < 1 || $mois > 12) {
                $this->error('❌ Le mois doit être entre 1 et 12');
                return Command::FAILURE;
            }
        } else {
            // Par défaut: mois précédent (système congolais)
            $moisPrecedent = now()->subMonth();
            $mois = $moisPrecedent->month;
            $annee = $moisPrecedent->year;
        }

        $nomMois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ][$mois];

        $this->info("📅 Facturation pour: {$nomMois} {$annee}");

        // Vérifier s'il y a déjà des factures pour cette période
        $facturesExistantes = Facture::where('mois', $mois)
                                    ->where('annee', $annee)
                                    ->count();

        if ($facturesExistantes > 0 && !$this->option('force')) {
            $this->warn("⚠️  {$facturesExistantes} facture(s) existent déjà pour {$nomMois} {$annee}");
            
            if (!$this->confirm('Voulez-vous continuer et créer les factures manquantes?')) {
                $this->info('❌ Génération annulée');
                return Command::SUCCESS;
            }
        }

        // Générer les factures
        try {
            $facturesCreees = Facture::genererFacturesPourMois($mois, $annee);
            
            if ($facturesCreees > 0) {
                $this->info("✅ {$facturesCreees} nouvelle(s) facture(s) créée(s) pour {$nomMois} {$annee}");
                
                // Afficher un résumé
                $this->table(
                    ['Statut', 'Nombre'],
                    [
                        ['Factures créées', $facturesCreees],
                        ['Factures existantes', $facturesExistantes],
                        ['Total pour la période', $facturesCreees + $facturesExistantes]
                    ]
                );
                
                // Afficher les prochaines échéances
                $this->afficherProchainesEcheances($mois, $annee);
                
            } else {
                $this->info("ℹ️  Aucune nouvelle facture à créer pour {$nomMois} {$annee}");
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la génération des factures: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Affiche les prochaines échéances
     */
    private function afficherProchainesEcheances($mois, $annee)
    {
        $factures = Facture::with(['locataire', 'loyer.appartement.immeuble'])
                          ->where('mois', $mois)
                          ->where('annee', $annee)
                          ->where('statut_paiement', 'non_paye')
                          ->orderBy('date_echeance')
                          ->limit(10)
                          ->get();

        if ($factures->count() > 0) {
            $this->info("\n📋 Prochaines échéances:");
            
            $tableData = [];
            foreach ($factures as $facture) {
                $tableData[] = [
                    $facture->numero_facture,
                    $facture->locataire->nom . ' ' . $facture->locataire->prenom,
                    $facture->loyer->appartement->immeuble->nom . ' - Apt ' . $facture->loyer->appartement->numero,
                    number_format($facture->montant, 0, ',', ' ') . ' CDF',
                    $facture->date_echeance->format('d/m/Y')
                ];
            }
            
            $this->table(
                ['N° Facture', 'Locataire', 'Appartement', 'Montant', 'Échéance'],
                $tableData
            );
        }
    }
}
