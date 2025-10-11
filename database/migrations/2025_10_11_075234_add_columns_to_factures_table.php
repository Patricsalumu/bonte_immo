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
        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('loyer_id')->constrained('loyers')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->string('numero_facture')->unique(); // FAC00001
            $table->integer('mois'); // 1-12
            $table->integer('annee'); // 2024, 2025...
            $table->decimal('montant', 15, 2);
            $table->date('date_echeance');
            $table->enum('statut_paiement', ['non_paye', 'paye', 'paye_en_retard'])->default('non_paye');
            $table->date('date_paiement')->nullable();
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->text('notes')->nullable();
            
            // Index pour optimiser les recherches
            $table->index(['mois', 'annee']);
            $table->index(['statut_paiement']);
            $table->index(['date_echeance']);
            $table->index(['locataire_id', 'mois', 'annee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['loyer_id']);
            $table->dropForeign(['locataire_id']);
            $table->dropIndex(['mois', 'annee']);
            $table->dropIndex(['statut_paiement']);
            $table->dropIndex(['date_echeance']);
            $table->dropIndex(['locataire_id', 'mois', 'annee']);
            
            $table->dropColumn([
                'loyer_id',
                'locataire_id', 
                'numero_facture',
                'mois',
                'annee',
                'montant',
                'date_echeance',
                'statut_paiement',
                'date_paiement',
                'montant_paye',
                'notes'
            ]);
        });
    }
};
