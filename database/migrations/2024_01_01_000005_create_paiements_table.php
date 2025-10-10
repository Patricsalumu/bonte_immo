<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyer_id')->constrained('loyers')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->enum('mode_paiement', ['cash', 'virement', 'mobile_money', 'garantie_locative']);
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->boolean('est_annule')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};