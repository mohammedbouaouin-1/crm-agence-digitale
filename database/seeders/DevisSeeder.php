<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Devis;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DevisSeeder extends Seeder
{
    public function run(): void
    {
        $client1 = Client::first();
        if ($client1) {
            $devis1 = Devis::firstOrCreate(
                ['numero' => 'DEV-2026-0001'],
                [
                    'client_id' => $client1->id,
                    'titre' => 'Conception Site Web & Moteur de Réservation',
                    'montant' => 18000,
                    'date_emission' => Carbon::now()->subMonths(2),
                    'date_validite' => Carbon::now()->subMonth(),
                    'statut' => 'accepte',
                    'description' => 'Développement d\'un site web vitrine responsive avec moteur de réservation direct, intégration multilingue et optimisation SEO locale.',
                    'conditions' => 'Acompte de 30% à la signature, 70% à la livraison finale.',
                ]
            );

            $premierProjet = $client1->projets()->first();
            if ($premierProjet && ! $premierProjet->devis_id) {
                $premierProjet->update(['devis_id' => $devis1->id]);
            }
        }

        $client2 = Client::skip(1)->first();
        if ($client2) {
            Devis::firstOrCreate(
                ['numero' => 'DEV-2026-0002'],
                [
                    'client_id' => $client2->id,
                    'titre' => 'Stratégie Publicitaire & Campagnes Google Ads',
                    'montant' => 12000,
                    'date_emission' => Carbon::now()->subWeeks(2),
                    'date_validite' => Carbon::now()->addWeeks(2),
                    'statut' => 'envoye',
                    'description' => 'Audit sémantique, création des groupes d\'annonces et gestion des enchères Google Ads sur une période de 3 mois.',
                    'conditions' => 'Règlement mensuel par prélèvement ou virement.',
                ]
            );
        }
    }
}
