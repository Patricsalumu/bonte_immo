<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comptes_financiers', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes avec les bons noms
            if (!Schema::hasColumn('comptes_financiers', 'nom')) {
                $table->string('nom')->nullable();
            }
            if (!Schema::hasColumn('comptes_financiers', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('comptes_financiers', 'solde')) {
                $table->decimal('solde', 12, 2)->default(0);
            }
            
            // Ajouter les nouvelles colonnes
            if (!Schema::hasColumn('comptes_financiers', 'gestionnaire_id')) {
                $table->unsignedBigInteger('gestionnaire_id')->nullable();
                $table->foreign('gestionnaire_id')->references('id')->on('users');
            }
            
            if (!Schema::hasColumn('comptes_financiers', 'actif')) {
                $table->boolean('actif')->default(true);
            }
            
            if (!Schema::hasColumn('comptes_financiers', 'autoriser_decouvert')) {
                $table->boolean('autoriser_decouvert')->default(false);
            }
        });

        // Copier les données des anciennes colonnes vers les nouvelles
        DB::table('comptes_financiers')->update([
            'nom' => DB::raw('nom_compte'),
            'type' => DB::raw('type_compte'),
            'solde' => DB::raw('solde_actuel')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comptes_financiers', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            if (Schema::hasColumn('comptes_financiers', 'gestionnaire_id')) {
                $table->dropForeign(['gestionnaire_id']);
                $table->dropColumn('gestionnaire_id');
            }
            
            if (Schema::hasColumn('comptes_financiers', 'actif')) {
                $table->dropColumn('actif');
            }
            
            if (Schema::hasColumn('comptes_financiers', 'autoriser_decouvert')) {
                $table->dropColumn('autoriser_decouvert');
            }
            
            // Supprimer les nouvelles colonnes de nom
            if (Schema::hasColumn('comptes_financiers', 'nom')) {
                $table->dropColumn('nom');
            }
            if (Schema::hasColumn('comptes_financiers', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('comptes_financiers', 'solde')) {
                $table->dropColumn('solde');
            }
        });
    }
};
