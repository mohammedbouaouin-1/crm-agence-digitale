# CRM Agence Digitale — ERP et Portail Client

Système de gestion intégré (ERP / CRM) et portail client sécurisé développé sur mesure pour les agences web, créatives et de marketing digital. Conçu avec **Laravel 12** et **Filament**, ce projet unifie la gestion de la relation client, le suivi des projets de développement web, le pilotage des campagnes publicitaires (Ads), ainsi qu'une facturation intelligente avec génération de factures PDF conformes et alertes automatisées.

---

## Contexte et Problématiques Métier

Dans une agence web moderne, le quotidien implique de jongler entre :
- Des clients aux profils variés (marques e-commerce, commerces locaux, comptes Instagram/TikTok, entreprises B2B).
- Des projets techniques avec des dates de livraison strictes, des réservations de noms de domaine et des jalons de recette.
- Des campagnes d'acquisition publicitaire (Meta Ads, Google Ads, TikTok Ads) dont le budget et les objectifs doivent être constamment surveillés.
- Une trésorerie tendue avec la gestion d'acomptes à la commande, de soldes à la livraison et de relances chronophages pour factures impayées.

Face à la dispersion des données (échanges WhatsApp, feuilles Excel volantes, devis et factures créés manuellement), ce système centralise l'ensemble de l'activité sur une plateforme unifiée à double entrée : un **espace d'administration** pour l'équipe de l'agence et un **portail client dédié** pour offrir une expérience transparente et professionnelle à chaque client.

---

## Points Forts et Fonctionnalités Clés

### 1. Architecture Dual-Panel (Double Espace Étanche)
L'application repose sur deux panneaux distincts gérés par Filament avec une sécurité stricte au niveau du modèle `User` :
- **Espace Agence (`/admin`)** : Réservé à l'équipe interne de l'agence (direction, chefs de projet, commerciaux).
- **Portail Client (`/client`)** : Espace épuré et sécurisé réservé aux clients pour suivre leurs projets, consulter leurs campagnes, régler leurs factures et solliciter l'assistance de l'agence.

---

### 2. Espace Agence et Administration (`/admin`)

#### Tableau de Bord Dynamique (Widgets en Temps Réel)
- **Indicateurs Clés (KPIs)** : Chiffre d'affaires global encaissé, montant des factures en attente/impayées, nombre de clients actifs et projets web en cours.
- **Graphique des Revenus (RevenueChart)** : Visualisation de l'évolution financière mensuelle.
- **Répartition Graphique** : Graphiques interactifs de la répartition des clients par statut (*Prospect*, *Actif*, *Inactif*) et des campagnes publicitaires en cours.
- **Flux d'Activités Récentes** : Suivi chronologique des derniers règlements, créations de projets et notes commerciales.

#### CRM et Fiches Clients 360°
- Fiche détaillée pour chaque client : contact principal, dénomination commerciale / page Instagram, secteur d'activité, adresse URL, coordonnées téléphoniques et statut.
- **Onglets Relationnels Imbriqués** : Chaque client regroupe directement ses projets, ses campagnes publicitaires, ses factures et son historique de notes.
- **Création d'Accès Client en 1 Clic** : Action automatique générant un mot de passe robuste aléatoire, créant le compte utilisateur rattaché et lui expédiant instantanément un e-mail de bienvenue contenant ses identifiants.
- **Sécurité des données (Soft Deletes)** : La suppression d'un client est réversible afin de préserver l'intégrité de l'historique comptable et fiscal.

#### Gestion des Projets Web
- Typologie de site : Vitrine, E-commerce, Refonte, Landing page, Application web.
- Suivi des budgets alloués et jalons calendaires (date de démarrage, date de livraison prévue vs date réelle).
- Suivi technique : Nom de domaine associé et URL du site en production ou pré-production.
- Statuts de suivi : *En attente*, *En cours*, *Recette*, *Livré*.

#### Pilotage des Campagnes Publicitaires (Ads)
- Gestion multi-plateformes : Meta Ads (Facebook/Instagram), Google Ads, TikTok Ads, LinkedIn Ads.
- Suivi du compte publicitaire cible, du lien de destination, des dates d'activation et du budget investi.
- Statuts opérationnels : *Planifiée*, *Active*, *En pause*, *Terminée*.

#### Facturation Intelligente et Règlements
- **Recalcul automatique du statut** : Le statut de la facture s'adapte en temps réel selon les paiements saisis :
  - `payee` : Montant total réglé.
  - `partiellement_payee` : Acompte versé, solde restant.
  - `en_retard` : Date d'échéance dépassée sans règlement intégral.
  - `en_attente` : Aucun versement reçu avant l'échéance.
- **Historique des Règlements** : Enregistrement des acomptes (virement, chèque, espèces) avec impact instantané sur la facture liée.
- **Génération et Téléchargement PDF instantané** : Moteur DomPDF intégré avec mise en page soignée, logo agence, décompte des acomptes et solde restant dû.
- **Relances par Email** : Envoi en un clic d'un e-mail de rappel personnalisé pour les factures impayées.

#### Suivi Commercial et Support
- **Notes Historiques** : Compte-rendu d'échanges téléphoniques, réunions et planification de la prochaine action de relance.
- **Traitement des Demandes Clients** : Boîte de réception des tickets envoyés par les clients avec marquage "Traité / En cours".

---

### 3. Espace Client Dédié (`/client`)

Chaque client dispose d'un espace épuré à son image :
- **Dashboard Personnel** : Synthèse de ses projets en cours et alerte visuelle sur ses éventuelles factures en attente.
- **Mes Projets** : Suivi de l'avancement de la conception de son site web avec accès direct aux liens de prévisualisation.
- **Mes Campagnes** : Visibilité sur les campagnes publicitaires actives et les budgets engagés.
- **Mes Factures** : Téléchargement direct des factures acquittées ou à régler au format PDF en un clic.
- **Centre de Demandes** : Formulaire direct pour soumettre une requête technique ou commerciale à l'équipe sans passer par des canaux dispersés.

---

### 4. Automatisation et Tâches Planifiées

- **Détection quotidienne des retards** : Une tâche planifiée (`app:mettre-a-jour-factures-en-retard`) s'exécute chaque nuit pour actualiser automatiquement les factures dont l'échéance est passée.
- **Protection des téléchargements** : La route de streaming du PDF (`/factures/{id}/pdf`) contrôle rigoureusement que le client connecté est bien le propriétaire légitime de la facture avant tout affichage.

---

## Pile Technologique

| Composant | Technologie | Description |
| :--- | :--- | :--- |
| **Framework Backend** | [Laravel 12](https://laravel.com) | Architecture MVC moderne, Eloquent ORM, Queues & Scheduling |
| **Back-Office & Portail** | [Filament](https://filamentphp.com) | Panneaux d'administration et portail basés sur le stack TALL |
| **Génération de PDF** | [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) | Édition de documents comptables professionnels |
| **Moteur Frontend** | [Livewire 3](https://livewire.laravel.com) & [Tailwind CSS](https://tailwindcss.com) | Composants réactifs en temps réel sans JavaScript complexe |
| **Base de Données** | SQLite / MySQL | Modèle relationnel complet avec Soft Deletes |
| **Mailing** | Laravel Mailables | Templates Blade HTML responsive pour l'onboarding et les relances |

---

## Organisation du Code Source

```
agence-admin/
├── app/
│   ├── Console/Commands/       # Commande artisan de mise à jour des retards de factures
│   ├── Filament/
│   │   ├── Client/             # PORTAIL CLIENT (Ressources, Pages et Widgets isolés)
│   │   │   ├── Resources/      # Campagnes, Demandes, Factures, Projets client
│   │   │   └── Widgets/        # Statistiques et alertes factures du client
│   │   ├── Resources/          # PANNEAU ADMIN (Gestion complète agence)
│   │   │   ├── Clients/        # Fiches clients, formulaires, tables & relation managers
│   │   │   ├── Projets/        # Gestion des sites web et livraisons
│   │   │   ├── Campagnes/      # Suivi Ads Meta, Google, TikTok
│   │   │   ├── Factures/       # Gestion comptable et téléchargement PDF
│   │   │   ├── Paiements/      # Saisie des règlements et acomptes
│   │   │   └── DemandeClients/ # Traitement des tickets de support
│   │   └── Widgets/            # Dashboard agence (Revenus, KPI, Graphiques)
│   ├── Mail/                   # Mailables (AccesClientCree, RelanceFacture, MessageClient)
│   ├── Models/                 # Modèles Eloquent (User, Client, Projet, Facture, Paiement...)
│   └── Providers/Filament/     # Configuration d'AdminPanelProvider et ClientPanelProvider
├── database/
│   ├── migrations/             # Schéma relationnel complet (12 tables)
│   └── seeders/                # Données initiales et démo (DemoSeeder avec comptes de test)
├── resources/views/
│   ├── emails/                 # Templates Blade des courriers électroniques
│   └── pdf/facture.blade.php   # Modèle HTML/CSS professionnel de la facture PDF
└── routes/
    ├── web.php                 # Route sécurisée de streaming PDF
    └── console.php             # Planification quotidienne du cron
```

---

## Installation et Démarrage Local

### Prérequis
- PHP 8.2 ou supérieur avec extensions courantes (`pdo`, `mbstring`, `openssl`, `gd`).
- Composer installé.
- Node.js (v18+) et NPM.

### 1. Cloner le projet
```bash
git clone https://github.com/mohammedbouaouin-1/crm-agence-digitale.git
cd crm-agence-digitale
```

### 2. Installer les dépendances PHP et JavaScript
```bash
composer install
npm install
```

### 3. Configurer l'environnement
Copiez le fichier d'exemple et générez la clé secrète d'application :
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Base de données & Données de démonstration
Initialisez la base de données et peuplez-la avec le jeu complet de test (clients, projets, factures, campagnes et comptes pré-configurés) :
```bash
php artisan migrate --seed
```

### 5. Compiler les assets et démarrer le serveur
Dans deux terminaux séparés (ou via `composer run dev`) :
```bash
# Terminal 1 : Compilation frontend
npm run dev

# Terminal 2 : Serveur local Laravel
php artisan serve
```

---

## Identifiants de Test (Issus du Seeder)

Après exécution de `php artisan migrate --seed`, vous pouvez vous connecter immédiatement :

### Espace Agence / Administration (`http://127.0.0.1:8000/admin`)
- **Email** : `admin@agence.com`
- **Mot de passe** : `password`

### Espace Client / Portail (`http://127.0.0.1:8000/client`)
- **Email** : `contact@client-demo.com` *(ou tout client créé via le bouton "Créer un accès")*
- **Mot de passe** : `password`

---

## Auteur et Remerciements

Développé par **Mohammed Bouaouin**  
- GitHub : [@mohammedbouaouin-1](https://github.com/mohammedbouaouin-1)
