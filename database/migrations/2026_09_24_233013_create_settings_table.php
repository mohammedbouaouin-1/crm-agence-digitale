<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Données par défaut pour Webmarko
        $defaults = [
            'agence_nom' => 'Webmarko',
            'agence_slogan' => 'Concepteur de sites web',
            'agence_email' => 'webmarko.company@gmail.com',
            'agence_telephone' => '06 61 51 11 83',
            'agence_adresse' => 'Avenue Bir Anzarane Résidence Nour 1er Etage Bureau N 9 Centre Ville, 30000 Fès',
            'agence_site_web' => 'https://webmarko.com',
            'agence_ice' => '002345678000092',
            'agence_rc' => '124580 Casablanca',
            'agence_if' => '45892301',
            'agence_patente' => '34567890',
            'banque_nom' => 'Attijariwafa Bank',
            'banque_titulaire' => 'Webmarko SARL',
            'banque_rib' => '007 780 0001234567890123 45',
            'devis_validite_jours' => '30',
            'facture_echeance_jours' => '15',
            'facture_mentions' => 'Règlement par virement bancaire sur le compte ci-dessus. Tout retard de paiement donnera lieu à des pénalités de retard conformément à la réglementation en vigueur.',
            'email_notifications' => 'webmarko.company@gmail.com',
        ];

        $now = now();
        foreach ($defaults as $key => $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
