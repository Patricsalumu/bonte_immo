<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appartement_id')->constrained('appartements')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->integer('mois'); // 1-12
            $table->integer('annee'); // 2024, 2025, etc.
            $table->decimal('montant', 10, 2);
            $table->enum('statut', ['impaye', 'partiel', 'paye'])->default('impaye');
            $table->date('date_echeance');
            $table->decimal('garantie_restante', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['appartement_id', 'mois', 'annee']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('loyers');
    }
};