<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loyers', function (Blueprint $table) {
            // Supprimer les colonnes liées aux anciens paiements qui existent encore
            if (Schema::hasColumn('loyers', 'mois')) {
                $table->dropColumn('mois');
            }
            if (Schema::hasColumn('loyers', 'annee')) {
                $table->dropColumn('annee');
            }
            if (Schema::hasColumn('loyers', 'date_echeance')) {
                $table->dropColumn('date_echeance');
            }
            if (Schema::hasColumn('loyers', 'garantie_restante')) {
                $table->dropColumn('garantie_restante');
            }
        });
        
        // Mettre à jour les données existantes avec des dates valides
        DB::table('loyers')->update([
            'date_debut' => now()->format('Y-m-d')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyers', function (Blueprint $table) {
            // Ajouter les colonnes supprimées
            $table->integer('mois');
            $table->integer('annee');
            $table->date('date_echeance');
            $table->decimal('garantie_restante', 10, 2)->default(0);
        });
    }
};
