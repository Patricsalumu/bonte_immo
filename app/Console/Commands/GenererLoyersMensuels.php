<?php

namespace App\Console\Commands;

use App\Models\Appartement;
use App\Models\Loyer;
use Illuminate\Console\Command;

class GenererLoyersMensuels extends Command
{
    protected $signature = 'loyers:generer {--mois=} {--annee=}';
    protected $description = 'Génère automatiquement les loyers mensuels pour tous les appartements occupés';

    public function handle()
    {
        $mois = $this->option('mois') ?? now()->month;
        $annee = $this->option('annee') ?? now()->year;

        $this->info("Génération des loyers pour {$mois}/{$annee}...");

        $appartementsOccupes = Appartement::where('statut', 'occupe')
            ->with(['locataire' => function($query) {
                $query->whereNull('date_sortie')
                      ->orWhere('date_sortie', '>', now());
            }])
            ->get();

        $loyersGeneres = 0;
        $loyersExistants = 0;

        foreach ($appartementsOccupes as $appartement) {
            if (!$appartement->locataire) {
                continue;
            }

            $locataire = $appartement->locataire;

            // Vérifier si le loyer existe déjà
            $loyerExistant = Loyer::where('appartement_id', $appartement->id)
                ->where('mois', $mois)
                ->where('annee', $annee)
                ->first();

            if ($loyerExistant) {
                $loyersExistants++;
                continue;
            }

            // Calculer la date d'échéance (fin du mois)
            $dateEcheance = now()->setYear($annee)->setMonth($mois)->endOfMonth();

            // Calculer la garantie restante
            $garantieRestante = $locataire->garantieRestante();

            // Créer le loyer
            Loyer::create([
                'appartement_id' => $appartement->id,
                'locataire_id' => $locataire->id,
                'mois' => $mois,
                'annee' => $annee,
                'montant' => $appartement->loyer_mensuel,
                'statut' => 'impaye',
                'date_echeance' => $dateEcheance,
                'garantie_restante' => $garantieRestante,
            ]);

            $loyersGeneres++;

            $this->line("✓ Loyer généré pour {$locataire->nom} - Apt {$appartement->numero} - {$appartement->loyer_mensuel} CDF");
        }

        $this->newLine();
        $this->info("Résumé de la génération :");
        $this->info("- Loyers générés : {$loyersGeneres}");
        $this->info("- Loyers déjà existants : {$loyersExistants}");
        $this->info("- Total appartements occupés : " . $appartementsOccupes->count());

        return Command::SUCCESS;
    }
}