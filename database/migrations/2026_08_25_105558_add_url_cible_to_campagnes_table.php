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
        Schema::table('campagnes', function (Blueprint $table) {
            $table->string('compte_cible')->nullable()->after('nom');
            $table->string('url_cible')->nullable()->after('plateforme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn(['compte_cible', 'url_cible']);
        });
    }
};
