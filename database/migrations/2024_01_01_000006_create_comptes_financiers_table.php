<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comptes_financiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom_compte');
            $table->enum('type_compte', ['caisse', 'banque', 'gestionnaire', 'charges', 'autre']);
            $table->decimal('solde_actuel', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comptes_financiers');
    }
};