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
        // D'abord supprimer la contrainte enum existante
        DB::statement("ALTER TABLE loyers MODIFY statut VARCHAR(20)");
        
        // Nettoyer les données existantes
        DB::table('loyers')->update(['statut' => 'actif']);
        
        // Remettre l'enum avec les nouvelles valeurs
        DB::statement("ALTER TABLE loyers MODIFY statut ENUM('actif', 'inactif') DEFAULT 'actif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retour à l'ancien enum
        DB::statement("ALTER TABLE loyers MODIFY statut ENUM('paye', 'impaye', 'en_retard') DEFAULT 'impaye'");
    }
};
