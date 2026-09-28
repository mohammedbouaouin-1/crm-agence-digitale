<?php

use App\Models\Campagne;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Projet;

test('un prospect bascule automatiquement en actif lors de la creation d un devis', function () {
    $client = Client::create([
        'nom' => 'Prospect Test Devis',
        'email' => 'prospect.devis@test.ma',
        'statut' => 'prospect',
    ]);

    expect($client->statut)->toBe('prospect');

    Devis::create([
        'client_id' => $client->id,
        'numero' => 'DEV-TEST-001',
        'titre' => 'Offre Web',
        'montant' => 15000,
        'date_emission' => now(),
        'date_validite' => now()->addDays(30),
        'statut' => 'envoye',
    ]);

    expect($client->fresh()->statut)->toBe('actif');
});

test('un prospect bascule automatiquement en actif lors de la creation d un projet', function () {
    $client = Client::create([
        'nom' => 'Prospect Test Projet',
        'email' => 'prospect.projet@test.ma',
        'statut' => 'prospect',
    ]);

    Projet::create([
        'client_id' => $client->id,
        'nom' => 'Site Vitrine',
        'type_site' => 'vitrine',
        'budget' => 8000,
        'date_debut' => now(),
        'statut' => 'developpement',
    ]);

    expect($client->fresh()->statut)->toBe('actif');
});

test('un prospect bascule automatiquement en actif lors de la creation d une campagne', function () {
    $client = Client::create([
        'nom' => 'Prospect Test Campagne',
        'email' => 'prospect.campagne@test.ma',
        'statut' => 'prospect',
    ]);

    Campagne::create([
        'client_id' => $client->id,
        'nom' => 'Campagne Google Ads',
        'type' => 'Google Ads',
        'plateforme' => 'Google',
        'budget' => 5000,
        'date_debut' => now(),
        'statut' => 'en_cours',
    ]);

    expect($client->fresh()->statut)->toBe('actif');
});

test('un prospect bascule automatiquement en actif lors de la creation d une facture', function () {
    $client = Client::create([
        'nom' => 'Prospect Test Facture',
        'email' => 'prospect.facture@test.ma',
        'statut' => 'prospect',
    ]);

    Facture::create([
        'client_id' => $client->id,
        'numero' => 'F-TEST-001',
        'montant' => 6000,
        'date_emission' => now(),
        'date_echeance' => now()->addDays(30),
    ]);

    expect($client->fresh()->statut)->toBe('actif');
});
