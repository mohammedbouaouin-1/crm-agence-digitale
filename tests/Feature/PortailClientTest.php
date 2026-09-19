<?php

use App\Models\Client;
use App\Models\Facture;
use App\Models\User;

it('empêche un client de voir les factures d\'un autre client', function () {
    $clientA = Client::factory()->create();
    $clientB = Client::factory()->create();

    $factureA = Facture::factory()->create(['client_id' => $clientA->id]);
    $factureB = Facture::factory()->create(['client_id' => $clientB->id]);

    $userA = User::factory()->create(['client_id' => $clientA->id]);
    $this->actingAs($userA);

    $facturesVisibles = Facture::where('client_id', auth()->user()->client_id)->pluck('id');

    expect($facturesVisibles)->toContain($factureA->id)
        ->and($facturesVisibles)->not->toContain($factureB->id);
});

it('bloque le téléchargement PDF si un client tente d\'accéder à la facture d\'un tiers', function () {
    $clientA = Client::factory()->create();
    $clientB = Client::factory()->create();

    $factureB = Facture::factory()->create(['client_id' => $clientB->id]);

    $userA = User::factory()->create(['client_id' => $clientA->id]);

    $response = $this->actingAs($userA)->get("/factures/{$factureB->id}/pdf");

    $response->assertStatus(403);
});