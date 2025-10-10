<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->text('adresse')->nullable();
            $table->foreignId('appartement_id')->nullable()->constrained('appartements')->onDelete('set null');
            $table->date('date_entree')->nullable();
            $table->date('date_sortie')->nullable();
            $table->decimal('garantie_initiale', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locataires');
    }
};