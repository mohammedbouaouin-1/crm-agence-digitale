<?php

use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->client_id !== null
            ? redirect('/client')
            : redirect('/admin');
    }

    return redirect('/admin');
});

Route::get('/factures/{facture}/pdf', function (Facture $facture) {
    $user = auth()->user();

    abort_unless(
        $user && ($user->client_id === null || $user->client_id === $facture->client_id),
        403
    );

    $facture->load(['client', 'paiements', 'devis']);
    $pdf = Pdf::loadView('pdf.facture', ['facture' => $facture]);

    return response()->stream(
        fn () => print ($pdf->output()),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="facture-'.$facture->numero.'.pdf"',
        ]
    );
})->middleware(['auth'])->name('factures.pdf');

Route::get('/devis/{devis}/pdf', function (Devis $devis) {
    $user = auth()->user();

    abort_unless(
        $user && ($user->client_id === null || $user->client_id === $devis->client_id),
        403
    );

    $devis->load(['client']);
    $pdf = Pdf::loadView('pdf.devis', ['devis' => $devis]);

    return response()->stream(
        fn () => print ($pdf->output()),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="devis-'.$devis->numero.'.pdf"',
        ]
    );
})->middleware(['auth'])->name('devis.pdf');

Route::get('/init-db', function () {
    try {
        @unlink(storage_path('framework/crm_installed.flag'));

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateLog = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => \Database\Seeders\DatabaseSeeder::class,
            '--force' => true,
        ]);
        $seedLog = \Illuminate\Support\Facades\Artisan::output();

        $admin = \App\Models\User::firstOrNew(['email' => 'admin@webmarko.com']);
        $admin->name = 'Admin Webmarko';
        $admin->password = \Illuminate\Support\Facades\Hash::make('password');
        $admin->client_id = null;
        $admin->save();

        $clientUser = \App\Models\User::firstOrNew(['email' => 'client@webmarko.com']);
        $clientUser->name = 'Client Webmarko';
        $clientUser->password = \Illuminate\Support\Facades\Hash::make('password');
        $clientUser->client_id = \App\Models\Client::first()?->id;
        $clientUser->save();

        @file_put_contents(storage_path('framework/crm_installed.flag'), 'READY');

        return response()->json([
            'status' => 'success',
            'message' => 'Base de données initialisée avec succès !',
            'admin_login' => [
                'email' => 'admin@webmarko.com',
                'password' => 'password',
            ],
            'client_login' => [
                'email' => 'client@webmarko.com',
                'password' => 'password',
            ],
            'stats' => [
                'users' => \App\Models\User::count(),
                'clients' => \App\Models\Client::count(),
                'factures' => \App\Models\Facture::count(),
                'devis' => \App\Models\Devis::count(),
            ],
            'migrate_log' => $migrateLog,
            'seed_log' => $seedLog,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});
