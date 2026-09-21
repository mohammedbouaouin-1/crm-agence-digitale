<?php

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\User;

beforeEach(function () {
    $this->client = Client::factory()->create([
        'nom' => 'Client Test',
        'email' => 'client.test@example.com',
    ]);

    $this->adminUser = User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin.test@example.com',
        'client_id' => null,
    ]);

    $this->clientUser = User::factory()->create([
        'name' => 'Client User Test',
        'email' => 'portal.test@example.com',
        'client_id' => $this->client->id,
    ]);
});

it('redirige la racine intelligemment selon le type d\'utilisateur', function () {
    // Visiteur non connecté -> /admin
    $this->get('/')->assertRedirect('/admin');

    // Utilisateur Admin -> /admin
    $this->actingAs($this->adminUser)->get('/')->assertRedirect('/admin');

    // Utilisateur Client -> /client
    $this->actingAs($this->clientUser)->get('/')->assertRedirect('/client');
});

it('permet a un admin d\'acceder a toutes les pages du panneau admin', function () {
    $this->actingAs($this->adminUser);

    $routes = [
        '/admin',
        '/admin/clients',
        '/admin/devis',
        '/admin/factures',
        '/admin/projets',
        '/admin/campagnes',
        '/admin/demande-clients',
        '/admin/paiements',
        '/admin/note-historiques',
    ];

    foreach ($routes as $route) {
        $response = $this->get($route);
        $response->assertSuccessful();
    }
});

it('bloque strictement un client qui tente d\'acceder au panneau admin', function () {
    $this->actingAs($this->clientUser);

    $routes = [
        '/admin',
        '/admin/clients',
        '/admin/devis',
        '/admin/factures',
        '/admin/projets',
        '/admin/campagnes',
    ];

    foreach ($routes as $route) {
        $this->get($route)->assertForbidden();
    }
});

it('permet a un client d\'acceder a toutes les pages de son portail', function () {
    $this->actingAs($this->clientUser);

    $routes = [
        '/client',
        '/client/projets',
        '/client/campagnes',
        '/client/factures',
        '/client/devis',
        '/client/demandes',
    ];

    foreach ($routes as $route) {
        $response = $this->get($route);
        $response->assertSuccessful();
    }
});

it('bloque un administrateur qui tente d\'acceder directement au portail client', function () {
    $this->actingAs($this->adminUser);

    $routes = [
        '/client',
        '/client/projets',
        '/client/campagnes',
        '/client/factures',
        '/client/devis',
    ];

    foreach ($routes as $route) {
        $this->get($route)->assertForbidden();
    }
});

it('genere correctement le PDF d\'un devis et d\'une facture sans erreur', function () {
    $devis = Devis::create([
        'client_id' => $this->client->id,
        'numero' => Devis::genererNumero(),
        'titre' => 'Refonte Web & SEO Local',
        'montant' => 15000,
        'date_emission' => now(),
        'date_validite' => now()->addDays(30),
        'statut' => 'accepte',
        'description' => "• Audit complet\n• Maquette UI/UX\n• Développement Laravel",
        'conditions' => 'Acompte 30% puis solde à la livraison',
        'accepte_le' => now(),
        'ip_acceptation' => '127.0.0.1',
    ]);

    $facture = Facture::create([
        'client_id' => $this->client->id,
        'devis_id' => $devis->id,
        'numero' => Facture::genererNumero(),
        'montant' => 4500,
        'date_emission' => now(),
        'date_echeance' => now()->addDays(15),
        'statut' => 'en_attente',
    ]);

    // Test Admin
    $this->actingAs($this->adminUser);
    $resDevis = $this->get("/devis/{$devis->id}/pdf");
    $resDevis->assertSuccessful();
    expect($resDevis->headers->get('content-type'))->toBe('application/pdf');

    $resFacture = $this->get("/factures/{$facture->id}/pdf");
    $resFacture->assertSuccessful();
    expect($resFacture->headers->get('content-type'))->toBe('application/pdf');

    // Test Client propriétaire
    $this->actingAs($this->clientUser);
    $this->get("/devis/{$devis->id}/pdf")->assertSuccessful();
    $this->get("/factures/{$facture->id}/pdf")->assertSuccessful();

    // Test tiers non autorisé
    $autreClient = Client::factory()->create();
    $autreUser = User::factory()->create(['client_id' => $autreClient->id]);
    $this->actingAs($autreUser);
    $this->get("/devis/{$devis->id}/pdf")->assertForbidden();
    $this->get("/factures/{$facture->id}/pdf")->assertForbidden();
});
