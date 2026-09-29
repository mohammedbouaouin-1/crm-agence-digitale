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
    }

    public function down(): void
    {
        User::whereIn('email', ['admin@webmarko.com', 'client@webmarko.com'])->delete();
    }
};
