<?php

use App\Models\Client;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@webmarko.com'],
            [
                'name' => 'Admin Webmarko',
                'password' => Hash::make('password'),
                'client_id' => null,
            ]
        );

        if (Client::count() === 0) {
            Artisan::call('db:seed', ['--class' => DemoSeeder::class, '--force' => true]);

            $client = Client::first();
            User::firstOrCreate(
                ['email' => 'client@webmarko.com'],
                [
                    'name' => 'Client Webmarko',
                    'password' => Hash::make('password'),
                    'client_id' => $client?->id,
                ]
            );
        }
    }

    public function down(): void
    {
        User::whereIn('email', ['admin@webmarko.com', 'client@webmarko.com'])->delete();
    }
};
