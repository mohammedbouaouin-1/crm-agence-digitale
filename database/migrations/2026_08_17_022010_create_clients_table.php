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
       Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->string('entreprise')->nullable();
    $table->string('email')->unique();
    $table->string('telephone')->nullable();
    $table->string('adresse')->nullable();
    $table->string('secteur_activite')->nullable();
    $table->enum('statut', ['prospect', 'actif', 'inactif'])->default('prospect');
    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
