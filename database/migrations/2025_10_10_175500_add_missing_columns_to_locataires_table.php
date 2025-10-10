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
        Schema::table('locataires', function (Blueprint $table) {
            // Ajouter les colonnes manquantes pour les locataires
            $table->string('prenom')->after('nom');
            $table->date('date_naissance')->nullable()->after('prenom');
            $table->string('profession')->nullable()->after('adresse');
            $table->string('employeur')->nullable()->after('profession');
            $table->decimal('revenu_mensuel', 10, 2)->nullable()->after('employeur');
            $table->string('numero_carte_identite')->nullable()->after('revenu_mensuel');
            $table->string('contact_urgence_nom')->nullable()->after('numero_carte_identite');
            $table->string('contact_urgence_telephone')->nullable()->after('contact_urgence_nom');
            $table->text('notes')->nullable()->after('contact_urgence_telephone');
            $table->boolean('actif')->default(true)->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->dropColumn([
                'prenom', 'date_naissance', 'profession', 'employeur',
                'revenu_mensuel', 'numero_carte_identite', 'contact_urgence_nom',
                'contact_urgence_telephone', 'notes', 'actif'
            ]);
        });
    }
};