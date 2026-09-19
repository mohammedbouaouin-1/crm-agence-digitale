<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DemoSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@webmarko.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $client = Client::first();

        User::updateOrCreate(
            ['email' => 'client@webmarko.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('password'),
                'client_id' => $client?->id,
            ]
        );
    }
}
