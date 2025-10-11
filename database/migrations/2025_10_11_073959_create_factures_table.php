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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
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
            $table->timestamps();
            
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
        Schema::dropIfExists('factures');
    }
};
