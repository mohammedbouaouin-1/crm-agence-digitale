<?php

use App\Mail\NouvelleFactureDisponible;
use App\Models\Client;
use App\Models\DemandeClient;
use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;

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

it('génère un numéro séquentiel de facture correct', function () {
    $numero = Facture::genererNumero();
    $annee = date('Y');

    expect($numero)->toStartWith("FAC-{$annee}-");
});

it('prépare un email avec PDF joint lors de l\'envoi d\'une facture au client', function () {
    $client = Client::factory()->create(['email' => 'client-facture@agence.ma']);
    $facture = Facture::factory()->create([
        'client_id' => $client->id,
        'montant' => 8500,
    ]);

    $mailable = new NouvelleFactureDisponible($facture);
    $mailable->build();

    expect($mailable->subject)->toContain('Nouvelle facture')
        ->and($mailable->subject)->toContain($facture->numero)
        ->and($mailable->rawAttachments)->toHaveCount(1)
        ->and($mailable->rawAttachments[0]['name'])->toBe("facture-{$facture->numero}.pdf");
});

it('affiche le titre du devis lié sur la facture générée', function () {
    $client = Client::factory()->create();
    $devis = Devis::factory()->create([
        'client_id' => $client->id,
        'titre' => 'Développement Plateforme SaaS',
    ]);
    $facture = Facture::factory()->create([
        'client_id' => $client->id,
        'devis_id' => $devis->id,
        'montant' => 20000,
    ]);

    $pdf = Pdf::loadView('pdf.facture', ['facture' => $facture]);
    $html = view('pdf.facture', ['facture' => $facture])->render();

    expect($html)->toContain('Développement Plateforme SaaS')
        ->and($html)->toContain($devis->numero);
});

it('permet de marquer une demande client comme traitée', function () {
    $client = Client::factory()->create();
    $demande = DemandeClient::create([
        'client_id' => $client->id,
        'sujet' => 'Question technique',
        'message' => 'Comment intégrer le tracking ?',
        'traite' => false,
    ]);

    $demande->update(['traite' => true]);

    expect($demande->fresh()->traite)->toBeTrue();
});
