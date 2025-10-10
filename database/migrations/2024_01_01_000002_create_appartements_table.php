<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appartements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immeuble_id')->constrained('immeubles')->onDelete('cascade');
            $table->string('numero');
            $table->decimal('surface', 8, 2)->nullable();
            $table->decimal('loyer_mensuel', 10, 2);
            $table->enum('statut', ['libre', 'occupe', 'sous_preavis'])->default('libre');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appartements');
    }
};