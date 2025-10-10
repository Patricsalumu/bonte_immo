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
        Schema::table('appartements', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            $table->string('type')->default('1_chambre')->after('numero');
            $table->decimal('superficie', 8, 2)->nullable()->after('type');
            $table->integer('etage')->default(0)->after('superficie');
            $table->decimal('garantie_locative', 10, 2)->nullable()->after('loyer_mensuel');
            $table->text('description')->nullable()->after('garantie_locative');
            $table->boolean('meuble')->default(false)->after('description');
            $table->boolean('disponible')->default(true)->after('meuble');
        });
        
        // Supprimer uniquement la colonne surface (garder statut)
        Schema::table('appartements', function (Blueprint $table) {
            if (Schema::hasColumn('appartements', 'surface')) {
                $table->dropColumn('surface');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appartements', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'superficie', 'etage', 'garantie_locative', 
                'description', 'meuble', 'disponible'
            ]);
            $table->decimal('surface', 8, 2)->nullable();
            $table->enum('statut', ['libre', 'occupe', 'sous_preavis'])->default('libre');
        });
    }
};
