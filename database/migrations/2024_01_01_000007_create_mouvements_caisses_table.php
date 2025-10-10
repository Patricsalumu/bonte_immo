<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mouvements_caisses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_source_id')->nullable()->constrained('comptes_financiers')->onDelete('cascade');
            $table->foreignId('compte_destination_id')->nullable()->constrained('comptes_financiers')->onDelete('cascade');
            $table->enum('type_mouvement', ['entree', 'sortie', 'transfert']);
            $table->decimal('montant', 15, 2);
            $table->string('mode_paiement')->nullable();
            $table->text('description');
            $table->string('categorie')->nullable(); // Pour les dépenses
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->date('date_operation');
            $table->boolean('est_annule')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mouvements_caisses');
    }
};