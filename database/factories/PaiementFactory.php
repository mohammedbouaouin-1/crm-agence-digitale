<?php

namespace Database\Factories;

use App\Models\Facture;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'facture_id' => Facture::factory(),
            'montant' => fake()->randomFloat(2, 200, 5000),
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
            'methode' => fake()->randomElement(['virement', 'cheque', 'especes']),
        ];
    }
}