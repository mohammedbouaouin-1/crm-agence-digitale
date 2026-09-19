<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->company(),
            'entreprise' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'telephone' => fake()->phoneNumber(),
            'adresse' => fake()->address(),
            'secteur_activite' => fake()->randomElement(['E-commerce', 'Restauration', 'Immobilier', 'Santé', 'Éducation']),
            'statut' => fake()->randomElement(['prospect', 'actif', 'inactif']),
        ];
    }
}