<?php

namespace App\Console\Commands;

use App\Models\Loyer;
use App\Models\Paiement;
use Illuminate\Console\Command;

class MettreAJourStatutsLoyers extends Command
{
    protected $signature = 'loyers:mettre-a-jour-statuts';
    protected $description = 'Met à jour les statuts des loyers en fonction des paiements effectués';

    public function handle()
    {
        $this->info('Mise à jour des statuts des loyers...');

        $loyers = Loyer::with('paiements')->get();
        $loyersMisAJour = 0;

        foreach ($loyers as $loyer) {
            $ancienStatut = $loyer->statut;
            $loyer->mettreAJourStatut();

            if ($ancienStatut !== $loyer->statut) {
                $loyersMisAJour++;
                $this->line("✓ Loyer {$loyer->id} : {$ancienStatut} → {$loyer->statut}");
            }
        }

        $this->newLine();
        $this->info("Résumé de la mise à jour :");
        $this->info("- Total loyers vérifiés : " . $loyers->count());
        $this->info("- Loyers mis à jour : {$loyersMisAJour}");

        return Command::SUCCESS;
    }
}