<?php

use App\Models\Campagne;
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

it('associe correctement une campagne à son devis d\'origine', function () {
    $client = Client::factory()->create();
    $devis = Devis::factory()->create([
        'client_id' => $client->id,
        'montant'   => 8000,
    ]);

    $campagne = Campagne::create([
        'client_id'  => $client->id,
        'devis_id'   => $devis->id,
        'nom'        => 'Campagne Meta Ads Promo',
        'type'       => 'Ads',
        'plateforme' => 'Meta Ads',
        'budget'     => $devis->montant,
        'date_debut' => now(),
        'statut'     => 'en_cours',
    ]);

    expect($campagne->devis)->not->toBeNull()
        ->and($campagne->devis->id)->toBe($devis->id)
        ->and((float) $campagne->budget)->toBe(8000.00)
        ->and($devis->fresh()->campagnes->pluck('id'))->toContain($campagne->id);
});

it('enregistre l\'horodatage et l\'adresse IP lors de l\'acceptation d\'un devis', function () {
    $client = Client::factory()->create();
    $devis = Devis::factory()->create([
        'client_id' => $client->id,
        'statut'    => 'envoye',
    ]);

    $now = now();
    $ip = '196.200.150.10';

    $devis->update([
        'statut'         => 'accepte',
        'accepte_le'     => $now,
        'ip_acceptation' => $ip,
    ]);

    $devisFresh = $devis->fresh();
    expect($devisFresh->statut)->toBe('accepte')
        ->and($devisFresh->accepte_le)->not->toBeNull()
        ->and($devisFresh->ip_acceptation)->toBe($ip);
});

it('prépare un email mailable avec PDF joint lors de l\'envoi d\'un devis', function () {
    $client = Client::factory()->create(['email' => 'client-test@agence.ma']);
    $devis = Devis::factory()->create([
        'client_id' => $client->id,
        'titre'     => 'Refonte portail web',
        'montant'   => 25000,
    ]);

    $mailable = new \App\Mail\NouveauDevisDisponible($devis);
    $mailable->build();

    expect($mailable->subject)->toContain('Devis ' . $devis->numero)
        ->and($mailable->rawAttachments)->toHaveCount(1)
        ->and($mailable->rawAttachments[0]['name'])->toBe("devis-{$devis->numero}.pdf");
});

it('calcule correctement la progression en pourcentage d\'un projet selon son statut', function () {
    $client = Client::factory()->create();

    $p1 = Projet::create(['client_id' => $client->id, 'nom' => 'P1', 'type_site' => 'vitrine', 'statut' => 'maquette', 'date_debut' => now()]);
    $p2 = Projet::create(['client_id' => $client->id, 'nom' => 'P2', 'type_site' => 'vitrine', 'statut' => 'developpement', 'date_debut' => now()]);
    $p3 = Projet::create(['client_id' => $client->id, 'nom' => 'P3', 'type_site' => 'vitrine', 'statut' => 'tests', 'date_debut' => now()]);
    $p4 = Projet::create(['client_id' => $client->id, 'nom' => 'P4', 'type_site' => 'vitrine', 'statut' => 'livre', 'date_debut' => now()]);
    $p5 = Projet::create(['client_id' => $client->id, 'nom' => 'P5', 'type_site' => 'vitrine', 'statut' => 'en_pause', 'date_debut' => now()]);

    expect($p1->progression)->toBe(25)
        ->and($p2->progression)->toBe(50)
        ->and($p3->progression)->toBe(75)
        ->and($p4->progression)->toBe(100)
        ->and($p5->progression)->toBe(0);
});

it('calcule correctement les métriques de pipeline et de conversion devis', function () {
    $client = Client::factory()->create();

    Devis::factory()->create(['client_id' => $client->id, 'statut' => 'envoye', 'montant' => 10000]);
    Devis::factory()->create(['client_id' => $client->id, 'statut' => 'accepte', 'montant' => 20000]);
    Devis::factory()->create(['client_id' => $client->id, 'statut' => 'refuse', 'montant' => 5000]);

    $pipeline = (float) Devis::where('statut', 'envoye')->sum('montant');
    $traites = Devis::whereIn('statut', ['accepte', 'refuse', 'expire', 'envoye'])->count();
    $acceptes = Devis::where('statut', 'accepte')->count();
    $taux = round(($acceptes / $traites) * 100, 1);

    expect($pipeline)->toBe(10000.0)
        ->and($traites)->toBe(3)
        ->and($acceptes)->toBe(1)
        ->and($taux)->toBe(33.3);
});


