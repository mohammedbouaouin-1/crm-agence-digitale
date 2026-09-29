<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
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
    }

    public function down(): void
    {
        User::where('email', 'admin@webmarko.com')->delete();
    }
};
