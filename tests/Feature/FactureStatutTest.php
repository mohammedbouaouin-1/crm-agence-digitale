<?php

use App\Models\Client;
use App\Models\Facture;

it('marque une facture comme payée quand le montant total est réglé', function () {
    $client = Client::factory()->create();
    $facture = Facture::factory()->create([
        'client_id' => $client->id,
        'montant' => 1000,
        'statut' => 'en_attente',
    ]);

    $facture->paiements()->create([
        'montant' => 1000,
        'date' => now(),
        'methode' => 'virement',
    ]);

    expect($facture->fresh()->statut)->toBe('payee');
});

it('marque une facture comme partiellement payée si le montant est incomplet', function () {
    $client = Client::factory()->create();
    $facture = Facture::factory()->create([
        'client_id' => $client->id,
        'montant' => 1000,
        'statut' => 'en_attente',
    ]);

    $facture->paiements()->create([
        'montant' => 400,
        'date' => now(),
        'methode' => 'virement',
    ]);

    expect($facture->fresh()->statut)->toBe('partiellement_payee');
});

it('marque une facture comme en retard si l\'échéance est dépassée sans solde', function () {
    $client = Client::factory()->create();
    $facture = Facture::factory()->create([
        'client_id' => $client->id,
        'montant' => 1000,
        'date_echeance' => now()->subDay(),
        'statut' => 'en_attente',
    ]);

    $facture->mettreAJourStatut();

    expect($facture->fresh()->statut)->toBe('en_retard');
});
