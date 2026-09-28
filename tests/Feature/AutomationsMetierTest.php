<?php

use App\Models\Campagne;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Projet;

test('un projet marque comme livre recoit automatiquement la date du jour en date_livraison_reelle si vide', function () {
    $client = Client::create([
        'nom' => 'Client Projet Auto',
        'email' => 'client.projet.auto@test.ma',
        'statut' => 'actif',
    ]);

    $projet = Projet::create([
        'client_id' => $client->id,
        'nom' => 'Refonte Web Platform',
        'type_site' => 'vitrine',
        'budget' => 12000,
        'date_debut' => now()->subDays(20),
        'statut' => 'developpement',
    ]);

    expect($projet->date_livraison_reelle)->toBeNull();

    $projet->update(['statut' => 'livre']);

    expect($projet->fresh()->date_livraison_reelle)->not->toBeNull();
    expect($projet->fresh()->date_livraison_reelle->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
});

test('une campagne marquee comme terminee recoit automatiquement la date du jour en date_fin si vide', function () {
    $client = Client::create([
        'nom' => 'Client Campagne Auto',
        'email' => 'client.campagne.auto@test.ma',
        'statut' => 'actif',
    ]);

    $campagne = Campagne::create([
        'client_id' => $client->id,
        'nom' => 'Campagne Facebook Ads',
        'type' => 'Meta Ads',
        'plateforme' => 'Facebook',
        'budget' => 4500,
        'date_debut' => now()->subDays(15),
        'statut' => 'en_cours',
    ]);

    expect($campagne->date_fin)->toBeNull();

    $campagne->update(['statut' => 'terminee']);

    expect($campagne->fresh()->date_fin)->not->toBeNull();
    expect($campagne->fresh()->date_fin->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
});

test('un devis dont la date de validite est depassee bascule automatiquement en statut expire', function () {
    $client = Client::create([
        'nom' => 'Client Devis Expire',
        'email' => 'client.devis.expire@test.ma',
        'statut' => 'actif',
    ]);

    $devis = Devis::create([
        'client_id' => $client->id,
        'numero' => 'DEV-EXP-001',
        'titre' => 'Proposition Web',
        'montant' => 10000,
        'date_emission' => now()->subDays(40),
        'date_validite' => now()->subDays(5),
        'statut' => 'envoye',
    ]);

    $devis->save();

    expect($devis->fresh()->statut)->toBe('expire');
});
