<?php

use App\Models\Client;
use App\Models\Devis;
use App\Models\Projet;
use App\Models\User;

it('génère un numéro séquentiel de devis correct', function () {
    $numero = Devis::genererNumero();
    $annee = date('Y');

    expect($numero)->toStartWith("DEV-{$annee}-");
});

it('bascule automatiquement le statut en expiré si la date de validité est dépassée', function () {
    $client = Client::factory()->create();
    $devis = Devis::factory()->create([
        'client_id'     => $client->id,
        'date_validite' => now()->subDay(),
        'statut'        => 'envoye',
    ]);

    $devis->save();

    expect($devis->fresh()->statut)->toBe('expire');
});

it('empêche un client de voir les devis d\'un autre client', function () {
    $clientA = Client::factory()->create();
    $clientB = Client::factory()->create();

    $devisA = Devis::factory()->create(['client_id' => $clientA->id]);
    $devisB = Devis::factory()->create(['client_id' => $clientB->id]);

    $userA = User::factory()->create(['client_id' => $clientA->id]);
    $this->actingAs($userA);

    $devisVisibles = Devis::where('client_id', auth()->user()->client_id)->pluck('id');

    expect($devisVisibles)->toContain($devisA->id)
        ->and($devisVisibles)->not->toContain($devisB->id);
});

it('bloque le téléchargement PDF si un client tente d\'accéder au devis d\'un tiers', function () {
    $clientA = Client::factory()->create();
    $clientB = Client::factory()->create();

    $devisB = Devis::factory()->create(['client_id' => $clientB->id]);

    $userA = User::factory()->create(['client_id' => $clientA->id]);

    $response = $this->actingAs($userA)->get("/devis/{$devisB->id}/pdf");

    $response->assertStatus(403);
});

it('autorise le client propriétaire à télécharger son propre devis en PDF', function () {
    $clientA = Client::factory()->create();
    $devisA = Devis::factory()->create(['client_id' => $clientA->id]);
    $userA = User::factory()->create(['client_id' => $clientA->id]);

    $response = $this->actingAs($userA)->get("/devis/{$devisA->id}/pdf");

    $response->assertStatus(200);
    expect($response->headers->get('content-type'))->toBe('application/pdf');
});

it('associe correctement un projet à son devis d\'origine', function () {
    $client = Client::factory()->create();
    $devis = Devis::factory()->create([
        'client_id' => $client->id,
        'montant'   => 15000,
    ]);

    $projet = Projet::create([
        'client_id'  => $client->id,
        'devis_id'   => $devis->id,
        'nom'        => 'Projet Dérivé',
        'type_site'  => 'e-commerce',
        'budget'     => $devis->montant,
        'date_debut' => now(),
        'statut'     => 'maquette',
    ]);

    expect($projet->devis)->not->toBeNull()
        ->and($projet->devis->id)->toBe($devis->id)
        ->and((float) $projet->budget)->toBe(15000.00);
});
