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
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->enum('type_site', ['vitrine', 'e-commerce', 'application_web', 'refonte'])->default('vitrine');
            $table->decimal('budget', 10, 2)->nullable();
            $table->date('date_debut');
            $table->date('date_livraison_prevue')->nullable();
            $table->date('date_livraison_reelle')->nullable();
            $table->enum('statut', ['maquette', 'developpement', 'tests', 'livre', 'en_pause'])->default('maquette');
            $table->string('nom_domaine')->nullable();
            $table->string('url_site')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
