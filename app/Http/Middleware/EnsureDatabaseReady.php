<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\User;
use Closure;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsureDatabaseReady
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        $flagFile = storage_path('framework/crm_installed.flag');

        if (! file_exists($flagFile)) {
            try {
                $adminExists = false;
                try {
                    $adminExists = User::where('email', 'admin@webmarko.com')->exists();
                } catch (\Throwable) {
                    $adminExists = false;
                }

                if (! $adminExists) {
                    Artisan::call('migrate', ['--force' => true]);

                    if (class_exists(DatabaseSeeder::class)) {
                        Artisan::call('db:seed', [
                            '--class' => DatabaseSeeder::class,
                            '--force' => true,
                        ]);
                    }

                    $admin = User::firstOrNew(['email' => 'admin@webmarko.com']);
                    $admin->name = $admin->name ?: 'Admin Webmarko';
                    $admin->password = Hash::make('password');
                    $admin->client_id = null;
                    $admin->save();

                    $clientUser = User::firstOrNew(['email' => 'client@webmarko.com']);
                    $clientUser->name = $clientUser->name ?: 'Client Webmarko';
                    $clientUser->password = Hash::make('password');
                    $clientUser->client_id = Client::first()?->id;
                    $clientUser->save();
                }

                @file_put_contents($flagFile, 'READY');
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $next($request);
    }
}
