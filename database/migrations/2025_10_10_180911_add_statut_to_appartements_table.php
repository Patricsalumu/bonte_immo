<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appartements', function (Blueprint $table) {
            // Ajouter la colonne statut si elle n'existe pas
            if (!Schema::hasColumn('appartements', 'statut')) {
                $table->enum('statut', ['libre', 'occupe', 'sous_preavis'])->default('libre')->after('disponible');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appartements', function (Blueprint $table) {
            // Supprimer la colonne statut si elle existe
            if (Schema::hasColumn('appartements', 'statut')) {
                $table->dropColumn('statut');
            }
        });
    }
};
