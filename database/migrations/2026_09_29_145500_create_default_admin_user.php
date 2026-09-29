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
        $admin = User::firstOrNew(['email' => 'admin@webmarko.com']);
        $admin->name = $admin->name ?: 'Admin Webmarko';
        $admin->password = Hash::make('password');
        $admin->client_id = null;
        $admin->save();

        if (Client::count() === 0) {
            Artisan::call('db:seed', ['--class' => DemoSeeder::class, '--force' => true]);
        }

        $client = Client::first();
        $clientUser = User::firstOrNew(['email' => 'client@webmarko.com']);
        $clientUser->name = $clientUser->name ?: 'Client Webmarko';
        $clientUser->password = Hash::make('password');
        $clientUser->client_id = $client?->id;
        $clientUser->save();
    }

    public function down(): void
    {
        User::whereIn('email', ['admin@webmarko.com', 'client@webmarko.com'])->delete();
    }
};
