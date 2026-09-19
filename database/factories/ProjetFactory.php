<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Projet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjetFactory extends Factory
{
    protected $model = Projet::class;

    public function definition(): array
    {
        $dateDebut = fake()->dateTimeBetween('-4 months', 'now');

        return [
            'client_id'             => Client::factory(),
            'nom'                   => 'Site ' . fake()->company(),
            'type_site'             => fake()->randomElement(['vitrine', 'e-commerce', 'application_web', 'refonte']),
            'budget'                => fake()->randomFloat(2, 5000, 60000),
            'date_debut'            => $dateDebut,
            'date_livraison_prevue' => fake()->dateTimeBetween($dateDebut, '+2 months'),
            'date_livraison_reelle' => fake()->optional(0.4)->dateTimeBetween($dateDebut, 'now'),
            'statut'                => fake()->randomElement(['maquette', 'developpement', 'tests', 'livre', 'en_pause']),
            'nom_domaine'           => fake()->optional(0.7)->domainName(),
            'url_site'              => fake()->optional(0.5)->url(),
        ];
    }
}
