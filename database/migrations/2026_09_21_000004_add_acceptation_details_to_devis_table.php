<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->timestamp('accepte_le')->nullable()->after('conditions');
            $table->string('ip_acceptation')->nullable()->after('accepte_le');
        });
    }

    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn(['accepte_le', 'ip_acceptation']);
        });
    }
};
