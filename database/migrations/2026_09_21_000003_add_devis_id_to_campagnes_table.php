<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->foreignId('devis_id')->nullable()->after('client_id')->constrained('devis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropForeign(['devis_id']);
            $table->dropColumn('devis_id');
        });
    }
};
