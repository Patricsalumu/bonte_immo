<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('compte_financier_id')->nullable()->after('actif');
            $table->foreign('compte_financier_id')->references('id')->on('comptes_financiers')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['compte_financier_id']);
            $table->dropColumn('compte_financier_id');
        });
    }
};
