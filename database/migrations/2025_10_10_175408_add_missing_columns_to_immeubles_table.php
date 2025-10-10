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
        Schema::table('immeubles', function (Blueprint $table) {
            $table->string('commune')->nullable()->after('adresse');
            $table->string('quartier')->nullable()->after('commune');
            $table->integer('nombre_etages')->default(1)->after('quartier');
            $table->decimal('valeur_estimee', 15, 2)->nullable()->after('description');
            $table->date('date_construction')->nullable()->after('valeur_estimee');
            $table->boolean('actif')->default(true)->after('date_construction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('immeubles', function (Blueprint $table) {
            $table->dropColumn(['commune', 'quartier', 'nombre_etages', 'valeur_estimee', 'date_construction', 'actif']);
        });
    }
};
