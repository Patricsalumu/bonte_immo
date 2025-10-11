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
        Schema::table('paiements', function (Blueprint $table) {
            // Ajouter la colonne facture_id après loyer_id
            $table->foreignId('facture_id')->nullable()->after('loyer_id')->constrained('factures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère puis la colonne
            $table->dropForeign(['facture_id']);
            $table->dropColumn('facture_id');
        });
    }
};
