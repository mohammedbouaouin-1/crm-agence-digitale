<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampagneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'nom' => fake()->catchPhrase(),
            'type' => fake()->randomElement(['SEO', 'Ads']),
            'plateforme' => fake()->randomElement(['Google Ads', 'Meta Ads', 'LinkedIn Ads', null]),
            'budget' => fake()->randomFloat(2, 500, 10000),
            'date_debut' => fake()->dateTimeBetween('-3 months', 'now'),
            'date_fin' => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'statut' => fake()->randomElement(['en_cours', 'terminee', 'en_pause']),
        ];
    }
}