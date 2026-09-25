# CRM Agence Digitale — ERP et Portail Client Webmarko

Système de gestion intégré (ERP / CRM) et portail client sécurisé développé sur mesure pour les agences web, créatives et de marketing digital. Conçu avec **Laravel 12** et **Filament**, ce projet unifie la gestion de la relation client, le suivi des projets de développement web, le pilotage des campagnes publicitaires (Ads), ainsi qu'une facturation intelligente avec génération de devis et factures PDF conformes et alertes automatisées.

---

## Contexte et Problematiques Metier

Dans une agence web moderne, le quotidien implique de jongler entre :
- Des clients aux profils variés (marques e-commerce, commerces locaux, comptes Instagram/TikTok, entreprises B2B).
- Des devis et propositions commerciales à arbitrer rapidement avec suivi des conversions.
- Des projets techniques avec des dates de livraison strictes, des réservations de noms de domaine et des jalons de recette.
- Des campagnes d'acquisition publicitaire (Meta Ads, Google Ads, TikTok Ads) dont le budget et les objectifs doivent être constamment surveillés.
- Une trésorerie tendue avec la gestion d'acomptes à la commande, de soldes à la livraison et de relances chronophages pour factures impayées.

Face à la dispersion des données (échanges WhatsApp, feuilles Excel volantes, devis et factures créés manuellement), ce système centralise l'ensemble de l'activité sur une plateforme unifiée à double entrée : un **espace d'administration** pour l'équipe de l'agence et un **portail client dédié** pour offrir une expérience transparente et professionnelle à chaque client.

---

## Points Forts et Fonctionnalites Cles

### 1. Architecture Dual-Panel (Double Espace Etanche)
L'application repose sur deux panneaux distincts gérés par Filament avec une sécurité stricte au niveau du modèle `User` :
- **Espace Agence (`/admin`)** : Réservé à l'équipe interne de l'agence (direction, chefs de projet, commerciaux).
- **Portail Client (`/client`)** : Espace épuré et sécurisé réservé aux clients pour suivre leurs projets, consulter leurs campagnes, régler leurs factures, valider leurs devis et solliciter l'assistance de l'agence.

---

### 2. Espace Agence et Administration (`/admin`)

#### Tableau de Bord Dynamique (Widgets en Temps Reel)
- **Indicateurs Cles (KPIs)** : Chiffre d'affaires global encaissé, montant des factures en attente/impayées, nombre de clients actifs et projets web en cours.
- **Graphique des Revenus (RevenueChart)** : Visualisation de l'évolution financière mensuelle.
- **Repartition Graphique** : Graphiques interactifs de la répartition des clients par statut (*Prospect*, *Actif*, *Inactif*) et des campagnes publicitaires en cours.
- **Flux d'Activites Recentes** : Suivi chronologique des derniers règlements, créations de projets et notes commerciales.
- **Badges Dynamiques de Navigation** : Affichage en direct du nombre de devis en attente, factures en retard et demandes client non traitées directement dans la barre latérale.

#### CRM et Fiches Clients 360°
- Fiche détaillée pour chaque client : contact principal, dénomination commerciale / page Instagram, secteur d'activité, adresse URL, coordonnées téléphoniques et statut.
- **Onglets Relationnels Imbriques** : Chaque client regroupe directement ses projets, ses campagnes publicitaires, ses devis, ses factures et son historique de notes.
- **Creation d'Acces Client en 1 Clic** : Action automatique générant un mot de passe robuste aléatoire, créant le compte utilisateur rattaché et lui expédiant instantanément un e-mail de bienvenue contenant ses identifiants.
- **Securite des donnees (Soft Deletes)** : La suppression d'un client est réversible afin de préserver l'intégrité de l'historique comptable et fiscal.

#### Devis et Propositions Commerciales
- **Cycle complet de devis** : Création, émission et suivi des propositions commerciales avec statuts dynamiques (*Brouillon*, *Envoyé*, *Accepté*, *Refusé*, *Expiré*).
- **Notification automatique par Email** : Envoi direct au client avec le PDF officiel en pièce jointe (`NouveauDevisDisponible`).
- **Acceptation Electronique Certifiee** : Le client valide le devis directement depuis son portail ; l'application horodate la validation et enregistre l'adresse IP du signataire.
- **Edition PDF officielle conforme** : Modèle de devis aux normes avec tableau des prestations détaillées, calcul automatique des remises et totaux HT/TTC, mentions légales et cartouche d'acceptation certifiée.
- **Conversion en 1 clic** :
  - Création automatique du **Projet Web** à partir du devis accepté avec report instantané du montant dans le budget.
  - Création automatique de la **Campagne Ads / SEO** avec héritage du budget devisé.
  - Émission de **Factures** d'acompte (30%, 50%) ou de solde (100%) rattachées directement au devis.

#### Gestion des Projets Web
- Typologie de site : Vitrine, E-commerce, Refonte, Landing page, Application web.
- Suivi des budgets alloués et jalons calendaires (date de démarrage, date de livraison prévue vs date réelle).
- Suivi technique : Nom de domaine associé et URL du site en production ou pré-production.
- Statuts de suivi : *En attente*, *En cours*, *Recette*, *Livré*.
- **Progression Visuelle des Projets** : Calcul dynamique du pourcentage d'avancement (Maquette : 25%, Développement : 50%, Tests : 75%, Livré : 100%) avec badges colorés.

#### Pilotage des Campagnes Publicitaires (Ads)
- Gestion multi-plateformes : Meta Ads (Facebook/Instagram), Google Ads, TikTok Ads, LinkedIn Ads.
- Suivi du compte publicitaire cible, du lien de destination, des dates d'activation et du budget investi.
- Statuts opérationnels : *Planifiée*, *Active*, *En pause*, *Terminée*.

#### Facturation Intelligente et Reglements
- **Recalcul automatique du statut** : Le statut de la facture s'adapte en temps réel selon les paiements saisis :
  - `payee` : Montant total réglé.
  - `partiellement_payee` : Acompte versé, solde restant.
  - `en_retard` : Date d'échéance dépassée sans règlement intégral.
  - `en_attente` : Aucun versement reçu avant l'échéance.
- **Historique des Reglements** : Enregistrement des acomptes (virement, chèque, espèces) avec impact instantané sur la facture liée.
- **Generation et Telechargement PDF instantane** : Moteur DomPDF intégré avec mise en page soignée, logo agence, décompte des acomptes et solde restant dû.
- **Relances par Email** : Envoi en un clic d'un e-mail de rappel personnalisé pour les factures impayées.

#### Suivi Commercial et Support
- **Notes Historiques** : Compte-rendu d'échanges téléphoniques, réunions et planification de la prochaine action de relance.
- **Traitement des Demandes Clients** : Boîte de réception des tickets envoyés par les clients avec marquage "Traité / En cours".

#### Paramètres Généraux de l'Agence
- **Configuration dynamique de l'agence** : Gestion directe du nom, coordonnées de contact et coordonnées bancaires (Banque, titulaire, RIB 24 chiffres pour les règlements clients).
- **Règles de facturation** : Délais d'échéance par défaut, durée de validité des devis et mentions légales de pied de page.
- **Répercussion instantanée sur les PDF** : Les coordonnées de l'agence et le RIB alimentent dynamiquement les devis et factures édités par le système.

---

### 3. Espace Client Dedie (`/client`)

Chaque client dispose d'un espace épuré à son image :
- **Dashboard Personnel** : Synthèse de ses projets en cours et alertes visuelles sur ses factures en attente.
- **Mes Devis** : Consultation des propositions commerciales, téléchargement du PDF officiel et acceptation en ligne certifiée.
- **Mes Projets** : Suivi de l'avancement de la conception de son site web avec accès direct aux liens de prévisualisation.
- **Mes Campagnes** : Visibilité sur les campagnes publicitaires actives et les budgets engagés.
- **Mes Factures** : Téléchargement direct des factures acquittées ou à régler au format PDF en un clic.
- **Centre de Demandes** : Formulaire direct pour soumettre une requête technique ou commerciale à l'équipe sans dispersion.
- **Coordonnées & RIB** : Consultation en direct du compte bancaire officiel de l'agence (Banque, titulaire, RIB 24 chiffres avec bouton de copie instantanée en 1 clic), contacts directs (WhatsApp, appel direct, email), et géolocalisation du bureau de Fès sur Google Maps.

---

### 4. Automatisation et Performances

- **Detection quotidienne des retards** : Une tâche planifiée (`app:mettre-a-jour-factures-en-retard`) s'exécute chaque nuit pour actualiser automatiquement les factures dont l'échéance est passée.
- **Optimisation Eloquent (Eager Loading)** : Préchargement optimisé des relations pour éliminer les requêtes redondantes (N+1) sur les tables de l'administration.
- **Protection des telechargements** : Les routes de streaming PDF (`/factures/{id}/pdf` et `/devis/{id}/pdf`) contrôlent rigoureusement que le client connecté est bien le propriétaire légitime du document avant tout affichage.
- **Design System Personnalise** : Feuille de style dédiée (`public/css/filament-theme.css`) assurant un rendu visuel épuré, typographie moderne et contrastes harmonieux en mode sombre et clair.

---

## Pile Technologique

| Composant | Technologie | Description |
| :--- | :--- | :--- |
| **Framework Backend** | [Laravel 12](https://laravel.com) | Architecture MVC moderne, Eloquent ORM, Queues & Scheduling |
| **Back-Office & Portail** | [Filament](https://filamentphp.com) | Panneaux d'administration et portail basés sur le stack TALL |
| **Generation de PDF** | [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) | Edition de documents comptables professionnels |
| **Moteur Frontend** | [Livewire 3](https://livewire.laravel.com) & [Tailwind CSS](https://tailwindcss.com) | Composants réactifs en temps réel sans JavaScript complexe |
| **Base de Donnees** | MySQL / SQLite | Modèle relationnel complet avec Soft Deletes et indexation |
| **Mailing** | Laravel Mailables | Templates Blade HTML responsive pour l'onboarding, les devis et les relances |

---

## Organisation du Code Source

```
agence-admin/
├── app/
│   ├── Console/Commands/       # Commande artisan de mise à jour des retards de factures
│   ├── Filament/
│   │   ├── Client/             # PORTAIL CLIENT (Ressources, Pages et Widgets isolés)
│   │   │   ├── Pages/          # Pages client (CoordonneesAgence)
│   │   │   ├── Resources/      # Campagnes, Demandes, Devis, Factures, Projets client
│   │   │   └── Widgets/        # Statistiques et alertes factures du client
│   │   ├── Pages/              # Pages personnalisées Filament (ParametresAgence)
│   │   ├── Resources/          # PANNEAU ADMIN (Gestion complète agence)
│   │   │   ├── Clients/        # Fiches clients, formulaires, tables & relation managers
│   │   │   ├── Devis/          # Gestion complète des devis et conversion
│   │   │   ├── Projets/        # Gestion des sites web et livraisons
│   │   │   ├── Campagnes/      # Suivi Ads Meta, Google, TikTok
│   │   │   ├── Factures/       # Gestion comptable et téléchargement PDF
│   │   │   ├── Paiements/      # Saisie des règlements et acomptes
│   │   │   ├── DemandeClients/ # Traitement des tickets de support
│   │   │   └── NoteHistoriques/# Journal des échanges commerciaux
│   │   └── Widgets/            # Dashboard agence (Revenus, KPI, Graphiques)
│   ├── Mail/                   # Mailables (AccesClientCree, RelanceFacture, NouveauDevisDisponible...)
│   ├── Models/                 # Modèles Eloquent (User, Client, Devis, Projet, Facture, Setting...)
│   └── Providers/Filament/     # Configuration d'AdminPanelProvider et ClientPanelProvider
├── database/
│   ├── migrations/             # Schéma relationnel complet (14 tables)
│   └── seeders/                # Données initiales et démo (DatabaseSeeder avec comptes de test)
├── public/
│   └── css/filament-theme.css  # Styles CSS personnalisés du CRM
├── resources/views/
│   ├── emails/                 # Templates Blade des courriers électroniques
│   └── pdf/                    # Modèles HTML/CSS professionnels (devis.blade.php, facture.blade.php)
└── routes/
    ├── web.php                 # Routes sécurisées de streaming PDF (devis et factures)
    └── console.php             # Planification quotidienne du cron
```

---

## Installation et Demarrage Local

### Prerequis
- PHP 8.2 ou supérieur avec extensions courantes (`pdo`, `mbstring`, `openssl`, `gd`, `mysqli`).
- Composer installé.
- Node.js (v18+) et NPM.
- Serveur MySQL (ex: XAMPP).

### 1. Cloner le projet
```bash
git clone https://github.com/mohammedbouaouin-1/crm-agence-digitale.git
cd crm-agence-digitale
```

### 2. Installer les dependances
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

### 4. Base de donnees & Donnees de demonstration
Initialisez la base de données et peuplez-la avec le jeu complet de test :
```bash
php artisan migrate --seed
```

### 5. Demarrer le serveur local
```bash
php artisan serve
```

L'application est immédiatement accessible sur `http://127.0.0.1:8000`.

---

## Identifiants de Test (Issus du Seeder)

Après exécution de `php artisan migrate --seed`, vous pouvez vous connecter immédiatement :

### Espace Agence / Administration (`http://127.0.0.1:8000/admin`)
- **Email** : `admin@webmarko.com`
- **Mot de passe** : `password`

### Espace Client / Portail (`http://127.0.0.1:8000/client`)
- **Email** : `client@webmarko.com`
- **Mot de passe** : `password`
- **Compte associe** : Client ID 1 (Karim Alami — Nexus Tech)

---

## Tests et Qualite de Code

Le projet dispose d'une suite de tests automatisés couvrant la logique financière et l'étanchéité des données :

```bash
# Execution de la suite de tests automatises (6 tests, 9 assertions)
php artisan test

# Audit et formatage du style de code (PSR-12)
./vendor/bin/pint --test
```

---

## Auteur

Développé par **Mohammed Bouaouin**  
- GitHub : [@mohammedbouaouin-1](https://github.com/mohammedbouaouin-1)
