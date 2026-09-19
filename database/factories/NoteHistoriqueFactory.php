<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteHistoriqueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'type' => fake()->randomElement(['appel', 'email', 'reunion', 'autre']),
            'contenu' => fake()->sentence(12),
            'prochaine_action' => fake()->optional()->dateTimeBetween('now', '+2 weeks'),
        ];
    }
}