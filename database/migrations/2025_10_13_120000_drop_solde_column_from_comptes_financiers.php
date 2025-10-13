<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comptes_financiers', function (Blueprint $table) {
            if (Schema::hasColumn('comptes_financiers', 'solde')) {
                $table->dropColumn('solde');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comptes_financiers', function (Blueprint $table) {
            if (!Schema::hasColumn('comptes_financiers', 'solde')) {
                $table->decimal('solde', 12, 2)->default(0);
            }
        });
    }
};
