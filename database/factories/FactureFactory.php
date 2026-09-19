<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Facture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facture>
 */
class FactureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'client_id' => Client::factory(),
        'numero' => 'F-' . fake()->unique()->numerify('2026-###'),
        'montant' => fake()->randomFloat(2, 1000, 20000),
        'date_emission' => fake()->dateTimeBetween('-2 months', 'now'),
        'date_echeance' => fake()->dateTimeBetween('now', '+1 month'),
        'statut' => 'en_attente',
    ];
}
}
