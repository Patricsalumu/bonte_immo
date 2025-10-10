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
            // Ajouter la colonne locataire_id pour établir la relation avec les locataires
            $table->unsignedBigInteger('locataire_id')->nullable()->after('id');
            $table->foreign('locataire_id')->references('id')->on('locataires')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appartements', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            $table->dropForeign(['locataire_id']);
            $table->dropColumn('locataire_id');
        });
    }
};
