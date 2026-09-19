<?php

use App\Models\Facture;

test('le modèle Facture initialise correctement ses attributs comptables', function () {
    $facture = new Facture([
        'numero' => 'F-2026-001',
        'montant' => 5000,
        'statut' => 'en_attente',
    ]);

    expect($facture->numero)->toBe('F-2026-001')
        ->and($facture->montant)->toBe(5000)
        ->and($facture->statut)->toBe('en_attente');
});
