<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Devis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Devis>
 */
class DevisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id'     => Client::factory(),
            'numero'        => 'DEV-' . fake()->unique()->numerify('2026-####'),
            'titre'         => fake()->sentence(4),
            'montant'       => fake()->randomFloat(2, 5000, 30000),
            'date_emission' => fake()->dateTimeBetween('-1 month', 'now'),
            'date_validite' => fake()->dateTimeBetween('now', '+1 month'),
            'statut'        => 'envoye',
            'description'   => fake()->paragraph(),
            'conditions'    => 'Acompte de 30% à la commande, solde à la livraison.',
        ];
    }
}
