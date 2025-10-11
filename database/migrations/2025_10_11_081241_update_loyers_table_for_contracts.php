<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loyers', function (Blueprint $table) {
            // Supprimer les colonnes liées aux paiements
            $table->dropColumn(['mois', 'annee', 'date_echeance']);
            
            // Ajouter les colonnes pour la gestion de contrat
            $table->date('date_debut')->nullable()->after('montant');
            $table->date('date_fin')->nullable()->after('date_debut');
            $table->decimal('garantie_locative', 15, 2)->default(0)->after('date_fin');
            $table->text('notes')->nullable()->after('garantie_locative');
        });
        
        // Mettre à jour les données existantes avec des dates valides
        DB::table('loyers')->update([
            'date_debut' => Carbon::now()->format('Y-m-d'),
            'statut' => 'actif'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyers', function (Blueprint $table) {
            // Restaurer les anciennes colonnes
            $table->integer('mois');
            $table->integer('annee');
            $table->date('date_echeance');
            $table->decimal('garantie_restante', 15, 2)->default(0);
            
            // Supprimer les nouvelles colonnes
            $table->dropColumn(['date_debut', 'date_fin', 'garantie_locative', 'notes']);
            
            // Restaurer l'ancien enum de statut
            $table->enum('statut', ['paye', 'impaye', 'partiel', 'en_attente', 'en_retard'])->default('en_attente')->change();
        });
    }
};
