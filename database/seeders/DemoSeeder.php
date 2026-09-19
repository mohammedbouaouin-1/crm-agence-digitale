<?php

namespace Database\Seeders;

use App\Models\Campagne;
use App\Models\Client;
use App\Models\DemandeClient;
use App\Models\Facture;
use App\Models\NoteHistorique;
use App\Models\Paiement;
use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $clientsData = [
            [
                'nom' => 'Karim Benjelloun',
                'entreprise' => 'Riad Fès Authentic',
                'email' => 'contact@riadfes-authentic.ma',
                'telephone' => '+212 5 35 63 45 10',
                'adresse' => 'Derb El Miter, Médina de Fès',
                'secteur_activite' => 'Hôtellerie & Tourisme',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subMonths(5),
                'projets' => [
                    [
                        'nom' => 'Refonte Site Web & Moteur de Réservation',
                        'type_site' => 'vitrine',
                        'budget' => 18000,
                        'date_debut' => Carbon::now()->subMonths(2),
                        'date_livraison_prevue' => Carbon::now()->addMonth(),
                        'date_livraison_reelle' => null,
                        'statut' => 'developpement',
                        'nom_domaine' => 'riadfes-authentic.ma',
                        'url_site' => 'https://preprod.riadfes-authentic.ma',
                        'created_at' => Carbon::now()->subMonths(2),
                    ],
                ],
                'campagnes' => [
                    [
                        'nom' => 'Google Ads - Tourisme & Séjours de Charme Fès',
                        'compte_cible' => '@riadfes_officiel',
                        'url_cible' => 'https://riadfes-authentic.ma/reserver',
                        'type' => 'Ads',
                        'plateforme' => 'Google Ads',
                        'budget' => 4500,
                        'date_debut' => Carbon::now()->subMonths(1),
                        'date_fin' => Carbon::now()->addMonths(2),
                        'statut' => 'en_cours',
                        'created_at' => Carbon::now()->subMonths(1),
                    ],
                ],
                'factures' => [
                    [
                        'numero' => 'F-2026-001',
                        'montant' => 18000,
                        'date_emission' => Carbon::now()->subMonths(2),
                        'date_echeance' => Carbon::now()->subMonths(1),
                        'paiements' => [
                            ['montant' => 9000, 'methode' => 'virement', 'date' => Carbon::now()->subMonths(2)->addDays(3)],
                        ],
                    ],
                    [
                        'numero' => 'F-2026-002',
                        'montant' => 14500,
                        'date_emission' => Carbon::now()->subDays(5),
                        'date_echeance' => Carbon::now()->addDays(25),
                        'paiements' => [
                            ['montant' => 14500, 'methode' => 'virement', 'date' => Carbon::now()->subDays(2)],
                        ],
                    ],
                ],
                'notes' => [
                    [
                        'type' => 'reunion',
                        'contenu' => 'Validation de l\'architecture des chambres et du parcours de réservation.',
                        'prochaine_action' => Carbon::now()->addDays(10),
                    ],
                ],
                'demandes' => [
                    [
                        'sujet' => 'Ajout de la suite Royale dans la galerie photo',
                        'message' => 'Pouvez-vous ajouter les photos de la Suite Royale rénovée ?',
                        'traite' => false,
                    ],
                ],
            ],
            [
                'nom' => 'Dr. Fatima Zahra El Alami',
                'entreprise' => 'Clinique Dentaire Atlas',
                'email' => 'contact@clinique-atlas.ma',
                'telephone' => '+212 5 35 73 22 18',
                'adresse' => 'Avenue Hassan II, Fès',
                'secteur_activite' => 'Santé',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subMonths(4),
                'projets' => [
                    [
                        'nom' => 'Site Vitrine Médical & Prise de Rendez-vous',
                        'type_site' => 'vitrine',
                        'budget' => 12000,
                        'date_debut' => Carbon::now()->subMonths(3),
                        'date_livraison_prevue' => Carbon::now()->subMonth(),
                        'date_livraison_reelle' => Carbon::now()->subMonth(),
                        'statut' => 'livre',
                        'nom_domaine' => 'clinique-atlas.ma',
                        'url_site' => 'https://clinique-atlas.ma',
                        'created_at' => Carbon::now()->subMonths(3),
                    ],
                ],
                'campagnes' => [
                    [
                        'nom' => 'Google Ads Local - Urgences & Implants Fès',
                        'compte_cible' => '@clinique.atlas',
                        'url_cible' => 'https://clinique-atlas.ma/contact',
                        'type' => 'Ads',
                        'plateforme' => 'Google Ads',
                        'budget' => 3500,
                        'date_debut' => Carbon::now()->subMonths(2),
                        'date_fin' => Carbon::now()->addMonth(),
                        'statut' => 'en_cours',
                        'created_at' => Carbon::now()->subMonths(2),
                    ],
                ],
                'factures' => [
                    [
                        'numero' => 'F-2026-003',
                        'montant' => 12000,
                        'date_emission' => Carbon::now()->subMonths(3),
                        'date_echeance' => Carbon::now()->subMonths(2),
                        'paiements' => [
                            ['montant' => 12000, 'methode' => 'cheque', 'date' => Carbon::now()->subMonths(2)],
                        ],
                    ],
                    [
                        'numero' => 'F-2026-004',
                        'montant' => 8000,
                        'date_emission' => Carbon::now()->subDays(10),
                        'date_echeance' => Carbon::now()->addDays(20),
                        'paiements' => [
                            ['montant' => 8000, 'methode' => 'virement', 'date' => Carbon::now()->subDays(8)],
                        ],
                    ],
                ],
                'notes' => [],
                'demandes' => [
                    [
                        'sujet' => 'Modification des horaires de consultation',
                        'message' => 'Merci de modifier nos horaires de samedi : 9h à 13h.',
                        'traite' => true,
                    ],
                ],
            ],
            [
                'nom' => 'Mehdi Tazi',
                'entreprise' => 'Fès Artisans & Cuir',
                'email' => 'contact@artisans-cuir.ma',
                'telephone' => '+212 6 61 45 78 90',
                'adresse' => 'Quartier des Tanneurs, Fès',
                'secteur_activite' => 'E-commerce',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subMonths(3),
                'projets' => [
                    [
                        'nom' => 'Boutique E-commerce Maroquinerie & Cuir',
                        'type_site' => 'e-commerce',
                        'budget' => 25000,
                        'date_debut' => Carbon::now()->subMonths(2),
                        'date_livraison_prevue' => Carbon::now()->addMonth(),
                        'date_livraison_reelle' => null,
                        'statut' => 'developpement',
                        'nom_domaine' => 'artisans-cuir.ma',
                        'url_site' => 'https://dev.artisans-cuir.ma',
                        'created_at' => Carbon::now()->subMonths(2),
                    ],
                ],
                'campagnes' => [
                    [
                        'nom' => 'Meta Ads - Export Maroquinerie France & Europe',
                        'compte_cible' => '@artisans_cuir_maroc',
                        'url_cible' => 'https://artisans-cuir.ma/collection',
                        'type' => 'Ads',
                        'plateforme' => 'Meta Ads',
                        'budget' => 7000,
                        'date_debut' => Carbon::now()->subMonth(),
                        'date_fin' => Carbon::now()->addMonths(2),
                        'statut' => 'en_cours',
                        'created_at' => Carbon::now()->subMonth(),
                    ],
                ],
                'factures' => [
                    [
                        'numero' => 'F-2026-005',
                        'montant' => 12500,
                        'date_emission' => Carbon::now()->subMonths(1),
                        'date_echeance' => Carbon::now()->addDays(15),
                        'paiements' => [
                            ['montant' => 12500, 'methode' => 'virement', 'date' => Carbon::now()->subDays(20)],
                        ],
                    ],
                    [
                        'numero' => 'F-2026-006',
                        'montant' => 12500,
                        'date_emission' => Carbon::now()->subDays(4),
                        'date_echeance' => Carbon::now()->addDays(26),
                        'paiements' => [
                            ['montant' => 12500, 'methode' => 'virement', 'date' => Carbon::now()->subDays(1)],
                        ],
                    ],
                ],
                'notes' => [],
                'demandes' => [],
            ],
            [
                'nom' => 'Youssef Bennani',
                'entreprise' => 'Atlas Immobilier Prestige',
                'email' => 'direction@atlas-prestige.ma',
                'telephone' => '+212 6 61 88 99 00',
                'adresse' => 'Boulevard Allal Ben Abdellah, Fès',
                'secteur_activite' => 'Immobilier',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subMonths(2),
                'projets' => [
                    [
                        'nom' => 'Portail d\'Annonces Immobilières & Résidences Saiss',
                        'type_site' => 'application_web',
                        'budget' => 32000,
                        'date_debut' => Carbon::now()->subMonths(2),
                        'date_livraison_prevue' => Carbon::now()->subDays(10),
                        'date_livraison_reelle' => null,
                        'statut' => 'tests',
                        'nom_domaine' => 'atlas-prestige.ma',
                        'url_site' => 'https://test.atlas-prestige.ma',
                        'created_at' => Carbon::now()->subMonths(2),
                    ],
                ],
                'campagnes' => [
                    [
                        'nom' => 'Lead Gen Meta - Résidence Panoramique Route d\'Imouzzer',
                        'compte_cible' => '@atlas.immobilier',
                        'url_cible' => 'https://atlas-prestige.ma/residence-panoramique',
                        'type' => 'Ads',
                        'plateforme' => 'Meta Ads',
                        'budget' => 8000,
                        'date_debut' => Carbon::now()->subMonths(1),
                        'date_fin' => Carbon::now()->addMonth(),
                        'statut' => 'en_cours',
                        'created_at' => Carbon::now()->subMonths(1),
                    ],
                ],
                'factures' => [
                    [
                        'numero' => 'F-2026-007',
                        'montant' => 16000,
                        'date_emission' => Carbon::now()->subMonths(2),
                        'date_echeance' => Carbon::now()->subMonths(1),
                        'paiements' => [
                            ['montant' => 16000, 'methode' => 'virement', 'date' => Carbon::now()->subMonths(1)],
                        ],
                    ],
                    [
                        'numero' => 'F-2026-008',
                        'montant' => 16000,
                        'date_emission' => Carbon::now()->subMonth(),
                        'date_echeance' => Carbon::now()->subDays(10), // En retard
                        'paiements' => [],
                    ],
                ],
                'notes' => [
                    [
                        'type' => 'appel',
                        'contenu' => 'Relance pour le règlement de la facture de solde F-2026-008. Virement prévu cette semaine.',
                        'prochaine_action' => Carbon::now()->addDays(3),
                    ],
                ],
                'demandes' => [
                    [
                        'sujet' => 'Ajout de la visite virtuelle 3D',
                        'message' => 'Intégration du lien Matterport sur la page de la résidence.',
                        'traite' => false,
                    ],
                ],
            ],
            [
                'nom' => 'Salma Chraibi',
                'entreprise' => 'Boutique Kaftan Moderne',
                'email' => 'contact@kaftan-moderne.ma',
                'telephone' => '+212 6 62 11 22 33',
                'adresse' => 'Borj Fez, Fès',
                'secteur_activite' => 'E-commerce',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subMonths(2),
                'projets' => [
                    [
                        'nom' => 'Boutique E-commerce Caftans Haute Couture',
                        'type_site' => 'e-commerce',
                        'budget' => 16000,
                        'date_debut' => Carbon::now()->subMonths(2),
                        'date_livraison_prevue' => Carbon::now()->subMonth(),
                        'date_livraison_reelle' => Carbon::now()->subMonth(),
                        'statut' => 'livre',
                        'nom_domaine' => 'kaftan-moderne.ma',
                        'url_site' => 'https://kaftan-moderne.ma',
                        'created_at' => Carbon::now()->subMonths(2),
                    ],
                ],
                'campagnes' => [
                    [
                        'nom' => 'TikTok & Instagram Ads - Collection Mariage & Fêtes',
                        'compte_cible' => '@kaftan.moderne',
                        'url_cible' => 'https://kaftan-moderne.ma/collections/mariage',
                        'type' => 'Ads',
                        'plateforme' => 'Meta Ads',
                        'budget' => 5000,
                        'date_debut' => Carbon::now()->subMonths(1),
                        'date_fin' => Carbon::now()->addMonth(),
                        'statut' => 'en_cours',
                        'created_at' => Carbon::now()->subMonths(1),
                    ],
                ],
                'factures' => [
                    [
                        'numero' => 'F-2026-009',
                        'montant' => 16000,
                        'date_emission' => Carbon::now()->subMonths(2),
                        'date_echeance' => Carbon::now()->subMonth(),
                        'paiements' => [
                            ['montant' => 16000, 'methode' => 'carte', 'date' => Carbon::now()->subMonth()],
                        ],
                    ],
                ],
                'notes' => [],
                'demandes' => [],
            ],
            [
                'nom' => 'Tariq Berrada',
                'entreprise' => 'Café Restaurant La Médina',
                'email' => 'tariq@restaurant-lamedina.ma',
                'telephone' => '+212 5 35 62 01 44',
                'adresse' => 'Place Boujloud, Fès',
                'secteur_activite' => 'Restauration',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subMonth(),
                'projets' => [
                    [
                        'nom' => 'Menu Digital Interactif & Site Vitrine',
                        'type_site' => 'vitrine',
                        'budget' => 7500,
                        'date_debut' => Carbon::now()->subMonth(),
                        'date_livraison_prevue' => Carbon::now()->subDays(10),
                        'date_livraison_reelle' => Carbon::now()->subDays(10),
                        'statut' => 'livre',
                        'nom_domaine' => 'restaurant-lamedina.ma',
                        'url_site' => 'https://restaurant-lamedina.ma',
                        'created_at' => Carbon::now()->subMonth(),
                    ],
                ],
                'campagnes' => [
                    [
                        'nom' => 'Campagne Meta Ads - Dîners Gastronomiques',
                        'compte_cible' => '@lamedina.restaurant',
                        'url_cible' => 'https://restaurant-lamedina.ma/reservation',
                        'type' => 'Ads',
                        'plateforme' => 'Meta Ads',
                        'budget' => 2500,
                        'date_debut' => Carbon::now()->subDays(15),
                        'date_fin' => Carbon::now()->addMonths(1),
                        'statut' => 'en_cours',
                        'created_at' => Carbon::now()->subDays(15),
                    ],
                ],
                'factures' => [
                    [
                        'numero' => 'F-2026-010',
                        'montant' => 7500,
                        'date_emission' => Carbon::now()->subMonth(),
                        'date_echeance' => Carbon::now()->subDays(10),
                        'paiements' => [
                            ['montant' => 7500, 'methode' => 'especes', 'date' => Carbon::now()->subDays(10)],
                        ],
                    ],
                ],
                'notes' => [],
                'demandes' => [],
            ],
            [
                'nom' => 'Amine Idrissi',
                'entreprise' => 'Groupe Scolaire Al Majd',
                'email' => 'direction@gs-almajd.ma',
                'telephone' => '+212 5 35 76 10 00',
                'adresse' => 'Route de Sefrou, Fès',
                'secteur_activite' => 'Éducation',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subDays(12), // Client acquis ce mois-ci (+1)
                'projets' => [
                    [
                        'nom' => 'Refonte Portail Scolaire & Espace Parents',
                        'type_site' => 'refonte',
                        'budget' => 22000,
                        'date_debut' => Carbon::now()->subDays(10),
                        'date_livraison_prevue' => Carbon::now()->addDays(20),
                        'date_livraison_reelle' => null,
                        'statut' => 'developpement',
                        'nom_domaine' => 'gs-almajd.ma',
                        'url_site' => 'https://v2.gs-almajd.ma',
                        'created_at' => Carbon::now()->subDays(10),
                    ],
                ],
                'campagnes' => [],
                'factures' => [
                    [
                        'numero' => 'F-2026-011',
                        'montant' => 11000,
                        'date_emission' => Carbon::now()->subDays(8),
                        'date_echeance' => Carbon::now()->addDays(22),
                        'paiements' => [
                            ['montant' => 11000, 'methode' => 'virement', 'date' => Carbon::now()->subDays(5)],
                        ],
                    ],
                ],
                'notes' => [],
                'demandes' => [],
            ],
            [
                'nom' => 'Dr. Nadia Filali',
                'entreprise' => 'Pharmacie Centrale Fès',
                'email' => 'pharmacie.filali@gmail.com',
                'telephone' => '+212 5 35 64 30 20',
                'adresse' => 'Boulevard Mohammed V, Fès',
                'secteur_activite' => 'Santé',
                'statut' => 'actif',
                'created_at' => Carbon::now()->subDays(5), // Client acquis ce mois-ci (+2)
                'projets' => [
                    [
                        'nom' => 'Site Web Local & Service Garde 24/7',
                        'type_site' => 'vitrine',
                        'budget' => 6500,
                        'date_debut' => Carbon::now()->subDays(4),
                        'date_livraison_prevue' => Carbon::now()->addDays(15),
                        'date_livraison_reelle' => null,
                        'statut' => 'maquette',
                        'nom_domaine' => 'pharmacie-centrale-fes.ma',
                        'url_site' => 'https://pharmacie-centrale-fes.ma',
                        'created_at' => Carbon::now()->subDays(4),
                    ],
                ],
                'campagnes' => [],
                'factures' => [
                    [
                        'numero' => 'F-2026-012',
                        'montant' => 6500,
                        'date_emission' => Carbon::now()->subDays(3),
                        'date_echeance' => Carbon::now()->addDays(20),
                        'paiements' => [],
                    ],
                ],
                'notes' => [],
                'demandes' => [],
            ],
            [
                'nom' => 'Hassan Amrani',
                'entreprise' => 'Auto-École Ennakhil',
                'email' => 'contact@autoecole-ennakhil.ma',
                'telephone' => '+212 6 63 77 88 99',
                'adresse' => 'Avenue des FAR, Fès',
                'secteur_activite' => 'Éducation',
                'statut' => 'prospect',
                'created_at' => Carbon::now()->subMonths(1),
                'projets' => [],
                'campagnes' => [],
                'factures' => [],
                'notes' => [
                    [
                        'type' => 'appel',
                        'contenu' => 'Échange téléphonique sur le devis. Proposition envoyée.',
                        'prochaine_action' => Carbon::now()->addDays(7),
                    ],
                ],
                'demandes' => [],
            ],
            [
                'nom' => 'Kawtar Mansouri',
                'entreprise' => 'Maroc Export Trading',
                'email' => 'k.mansouri@maroc-export.ma',
                'telephone' => '+212 5 22 40 50 60',
                'adresse' => 'Casablanca Marina, Casablanca',
                'secteur_activite' => 'Immobilier',
                'statut' => 'inactif',
                'created_at' => Carbon::now()->subMonths(6),
                'projets' => [],
                'campagnes' => [],
                'factures' => [],
                'notes' => [],
                'demandes' => [],
            ],
        ];

        foreach ($clientsData as $data) {
            $client = Client::create([
                'nom' => $data['nom'],
                'entreprise' => $data['entreprise'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'adresse' => $data['adresse'],
                'secteur_activite' => $data['secteur_activite'],
                'statut' => $data['statut'],
                'created_at' => $data['created_at'],
            ]);

            foreach ($data['projets'] as $p) {
                $client->projets()->create($p);
            }

            foreach ($data['campagnes'] as $c) {
                $client->campagnes()->create($c);
            }

            foreach ($data['factures'] as $f) {
                $paiements = $f['paiements'];
                unset($f['paiements']);

                $facture = $client->factures()->create(array_merge($f, [
                    'statut' => 'en_attente',
                    'created_at' => $f['date_emission'],
                ]));

                foreach ($paiements as $paiement) {
                    $facture->paiements()->create(array_merge($paiement, [
                        'created_at' => $paiement['date'],
                    ]));
                }

                $facture->mettreAJourStatut();
            }

            foreach ($data['notes'] as $n) {
                $client->notes()->create($n);
            }

            foreach ($data['demandes'] as $d) {
                $client->demandes()->create($d);
            }
        }
    }
}