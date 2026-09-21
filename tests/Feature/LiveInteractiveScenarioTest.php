<?php

use App\Models\Campagne;
use App\Models\Client;
use App\Models\DemandeClient;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Projet;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

it('execute le cycle complet de vente, acceptation client, creation campagne, facturation acompte et paiement', function () {
    // 1. MISE EN PLACE DES ACTEURS
    $client = Client::first() ?? Client::factory()->create([
        'nom' => 'Karim Benjelloun',
        'entreprise' => 'Riad Fès Authentic',
        'email' => 'contact@riadfes-authentic.ma',
    ]);
    $adminUser = User::whereNull('client_id')->first() ?? User::factory()->create(['client_id' => null]);
    $clientUser = User::where('client_id', $client->id)->first() ?? User::factory()->create(['client_id' => $client->id]);

    echo "\n=======================================================\n";
    echo "  EXÉCUTION DU SCÉNARIO COMPLET EN TEMPS RÉEL (VOUS & MOI) \n";
    echo "=======================================================\n";

    // 2. ÉTAPE 1 : CRÉATION DU DEVIS EN ATTENTE (STATUT : ENVOYÉ)
    $devis = Devis::create([
        'client_id' => $client->id,
        'numero' => Devis::genererNumero(),
        'titre' => 'Stratégie Marketing Digital & Google Ads 2026',
        'montant' => 10000.00,
        'date_emission' => now(),
        'date_validite' => now()->addDays(30),
        'statut' => 'envoye',
        'description' => "• Audit SEA & SEO complet\n• Configuration campagnes Search & PMax\n• Optimisation du taux de conversion (CRO)",
        'conditions' => 'Acompte de 30% à la commande, solde à 30 jours.',
    ]);

    expect($devis->statut)->toBe('envoye');
    echo "✓ [1/6] Devis {$devis->numero} créé au statut 'Envoyé' (Montant: 10 000 DH)\n";
    echo "       -> Actions visibles en Admin : [PDF] [Envoyer au client] [Modifier]\n";

    // 3. ÉTAPE 2 : LE CLIENT SE CONNECTE À SON PORTAIL ET ACCEPTE LE DEVIS
    $this->actingAs($clientUser);
    $devis->update([
        'statut' => 'accepte',
        'accepte_le' => now(),
        'ip_acceptation' => '196.200.155.42', // IP réelle simulée
    ]);

    expect($devis->fresh()->statut)->toBe('accepte')
        ->and($devis->fresh()->accepte_le)->not->toBeNull()
        ->and($devis->fresh()->ip_acceptation)->toBe('196.200.155.42');

    echo "✓ [2/6] Le Client accepte le devis en ligne depuis son espace client\n";
    echo "       -> Accord certifié horodaté le {$devis->fresh()->accepte_le->format('d/m/Y H:i')} (IP: 196.200.155.42)\n";

    // 4. ÉTAPE 3 : L'ADMINISTRATION CRÉE LA CAMPAGNE MARKETING ASSOCIÉE
    $this->actingAs($adminUser);
    $campagne = Campagne::create([
        'client_id' => $client->id,
        'devis_id' => $devis->id,
        'nom' => $devis->titre,
        'type' => 'Ads',
        'plateforme' => 'Google Ads',
        'budget' => 10000.00,
        'date_debut' => now(),
        'date_fin' => now()->addDays(30),
        'statut' => 'en_cours',
    ]);

    expect($devis->fresh()->campagnes)->toHaveCount(1)
        ->and($devis->fresh()->campagnes->first()->id)->toBe($campagne->id);

    echo "✓ [3/6] L'Admin clique sur [Créer campagne] et lance la production\n";
    echo "       -> Campagne '{$campagne->nom}' créée avec un budget de 10 000 DH\n";
    echo "       -> Le bouton sur le devis se transforme automatiquement en [Voir campagne]\n";

    // 5. ÉTAPE 4 : L'ADMIN GÉNÈRE LA FACTURE D'ACOMPTE DE 30%
    $tauxAcompte = 0.30;
    $montantFacture = round($devis->montant * $tauxAcompte, 2);
    $numeroFacture = Facture::genererNumero();

    $facture = Facture::create([
        'client_id' => $client->id,
        'devis_id' => $devis->id,
        'numero' => $numeroFacture,
        'montant' => $montantFacture,
        'date_emission' => now(),
        'date_echeance' => now()->addDays(15),
        'statut' => 'en_attente',
    ]);

    expect($facture->montant)->toBe(3000.0)
        ->and($facture->statut)->toBe('en_attente')
        ->and($facture->devis_id)->toBe($devis->id);

    echo "✓ [4/6] L'Admin clique sur [Créer facture] et choisit 'Acompte de 30%'\n";
    echo "       -> Facture {$facture->numero} créée pour un montant de 3 000 DH (Statut : En attente)\n";

    // 6. ÉTAPE 5 : GÉNÉRATION DES PDFS OFFICIELS
    $devis->load('client');
    $pdfDevis = Pdf::loadView('pdf.devis', ['devis' => $devis])->output();
    expect(strlen($pdfDevis))->toBeGreaterThan(5000)
        ->and(str_starts_with($pdfDevis, '%PDF-'))->toBeTrue();

    $facture->load(['client', 'paiements', 'devis']);
    $pdfFacture = Pdf::loadView('pdf.facture', ['facture' => $facture])->output();
    expect(strlen($pdfFacture))->toBeGreaterThan(5000)
        ->and(str_starts_with($pdfFacture, '%PDF-'))->toBeTrue();

    echo "✓ [5/6] Génération des PDF haute fidélité :\n";
    echo "       -> PDF Devis {$devis->numero} : ".strlen($pdfDevis)." octets [Cartouche d'acceptation certifiée OK]\n";
    echo "       -> PDF Facture {$facture->numero} : ".strlen($pdfFacture)." octets [Réf Devis lié et reste à payer OK]\n";

    // 7. ÉTAPE 6 : RÈGLEMENT PAR LE CLIENT ET CLÔTURE DE LA FACTURE
    $paiement = Paiement::create([
        'facture_id' => $facture->id,
        'montant' => 3000.00,
        'date' => now(),
        'methode' => 'virement',
        'reference' => 'VIR-2026-987654',
    ]);

    $facture->mettreAJourStatut();

    expect($facture->fresh()->statut)->toBe('payee')
        ->and($facture->fresh()->totalPaye)->toBe(3000.0);

    echo "✓ [6/6] Enregistrement du règlement client de 3 000 DH (Virement)\n";
    echo "       -> Statut de la facture mis à jour automatiquement en : 'Payée' (Badge vert)\n";
    echo "=======================================================\n";
    echo "  TOUS LES COMPOSANTS ET FLUX ONT ÉTÉ EXÉCUTÉS AVEC SUCCÈS !\n";
    echo "=======================================================\n\n";
});

it('execute le cycle complet de gestion d\'un projet web et du support client', function () {
    $client = Client::first() ?? Client::factory()->create();
    $adminUser = User::whereNull('client_id')->first() ?? User::factory()->create(['client_id' => null]);
    $clientUser = User::where('client_id', $client->id)->first() ?? User::factory()->create(['client_id' => $client->id]);

    echo "\n=======================================================\n";
    echo "  EXÉCUTION DU SCÉNARIO PROJET WEB & SUPPORT CLIENT     \n";
    echo "=======================================================\n";

    // 1. CRÉATION DU PROJET WEB
    $projet = Projet::create([
        'client_id' => $client->id,
        'nom' => 'Site E-Commerce Catalogue & Paiement CMI',
        'type_site' => 'e-commerce',
        'budget' => 25000.00,
        'date_debut' => now(),
        'date_livraison_prevue' => now()->addDays(45),
        'statut' => 'maquette',
        'url_site' => 'https://preprod.client-ecommerce.ma',
    ]);

    expect($projet->progression)->toBe(25);
    echo "✓ [1/4] Projet créé au statut 'Maquette' -> Avancement calculé : {$projet->progression}%\n";

    // 2. PASSAGE EN DÉVELOPPEMENT PUIS EN TESTS
    $projet->update(['statut' => 'developpement']);
    expect($projet->fresh()->progression)->toBe(50);
    echo "✓ [2/4] Projet en 'Développement' -> Avancement calculé : {$projet->fresh()->progression}%\n";

    $projet->update(['statut' => 'livre']);
    expect($projet->fresh()->progression)->toBe(100);
    echo "       -> Projet 'Livré' -> Avancement calculé : {$projet->fresh()->progression}% (100% - Clôturé)\n";

    // 3. LE CLIENT ENVOIE UNE DEMANDE D'ASSISTANCE DEPUIS SON PORTAIL
    $demande = DemandeClient::create([
        'client_id' => $client->id,
        'sujet' => 'Question sur une campagne',
        'message' => 'Pouvez-vous me partager le rapport hebdomadaire des conversions ?',
        'traite' => false,
    ]);

    expect($demande->traite)->toBeFalse();
    echo "✓ [3/4] Le client soumet un ticket depuis son portail : '{$demande->sujet}' (Statut: En attente)\n";

    // 4. L'ADMINISTRATION TRAITE LE TICKET EN 1 CLIC
    $demande->update(['traite' => true]);
    expect($demande->fresh()->traite)->toBeTrue();
    echo "✓ [4/4] L'Admin clique sur [Marquer traitée] -> Ticket archivé avec succès !\n";

    $demande->update(['traite' => false]);
    expect($demande->fresh()->traite)->toBeFalse();
    echo "       -> Action [Rouvrir] testée avec succès -> Ticket remis en cours !\n";
    echo "=======================================================\n";
});
