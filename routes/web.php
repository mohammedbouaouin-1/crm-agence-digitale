<?php

use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/factures/{facture}/pdf', function (Facture $facture) {
    $user = auth()->user();

    abort_unless(
        $user->client_id === null || $user->client_id === $facture->client_id,
        403
    );

    $facture->load(['client', 'paiements']);
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
        $user->client_id === null || $user->client_id === $devis->client_id,
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
