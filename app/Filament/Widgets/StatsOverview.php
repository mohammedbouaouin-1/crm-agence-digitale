<?php

namespace App\Filament\Widgets;

use App\Models\Campagne;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $clientsActifs = Client::where('statut', 'actif')->count();
        $clientsActifsMoisDernier = Client::where('statut', 'actif')
            ->where('created_at', '<=', now()->subMonth())
            ->count();

        $campagnesEnCours = Campagne::where('statut', 'en_cours')->count();

        $facture_ce_mois = Facture::whereMonth('date_emission', now()->month)
            ->whereYear('date_emission', now()->year)
            ->sum('montant');

        $facture_mois_dernier = Facture::whereMonth('date_emission', now()->subMonth()->month)
            ->whereYear('date_emission', now()->subMonth()->year)
            ->sum('montant');

        $facturesEnRetard = Facture::where('statut', 'en_retard')->count();

        // Pipeline et conversion Devis
        $pipelineDevisMontant = (float) Devis::where('statut', 'envoye')->sum('montant');
        $devisEnAttenteCount = Devis::where('statut', 'envoye')->count();
        $devisTraites = Devis::whereIn('statut', ['accepte', 'refuse', 'expire', 'envoye'])->count();
        $devisAcceptes = Devis::where('statut', 'accepte')->count();
        $tauxConversion = $devisTraites > 0 ? round(($devisAcceptes / $devisTraites) * 100, 1) : 0;

        // Données des 6 derniers mois
        $clientsParMois = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo)->endOfMonth();
            return Client::where('statut', 'actif')
                ->where('created_at', '<=', $date)
                ->count();
        })->toArray();

        $campagnesParMois = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return Campagne::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        })->toArray();

        $factureParMois = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return (float) Facture::whereYear('date_emission', $date->year)
                ->whereMonth('date_emission', $date->month)
                ->sum('montant');
        })->toArray();

        $retardsParMois = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return Facture::where('statut', 'en_retard')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        })->toArray();

        $pipelineParMois = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return (float) Devis::where('statut', 'envoye')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('montant');
        })->toArray();

        $devisAcceptesParMois = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return Devis::where('statut', 'accepte')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        })->toArray();

        $diffClients = $clientsActifs - $clientsActifsMoisDernier;
        $labelClients = $diffClients >= 0 ? "+{$diffClients} ce mois" : "{$diffClients} ce mois";

        return [
            Stat::make('Clients actifs', new HtmlString("<span style='font-size: 1.45rem; font-weight: 700; letter-spacing: -0.02em;'>{$clientsActifs}</span>"))
                ->description($labelClients)
                ->color($diffClients >= 0 ? 'success' : 'danger')
                ->chart($clientsParMois),

            Stat::make('Pipeline devis', new HtmlString("<span style='font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em;'>" . number_format($pipelineDevisMontant, 0, ',', ' ') . " DH</span>"))
                ->description("{$devisEnAttenteCount} proposition(s) en attente")
                ->color($pipelineDevisMontant > 0 ? 'warning' : 'gray')
                ->chart($pipelineParMois),

            Stat::make('Taux de conversion devis', new HtmlString("<span style='font-size: 1.45rem; font-weight: 700; letter-spacing: -0.02em;'>{$tauxConversion}%</span>"))
                ->description("{$devisAcceptes} devis accepté(s) sur {$devisTraites}")
                ->color($tauxConversion >= 50 ? 'success' : ($tauxConversion > 0 ? 'info' : 'gray'))
                ->chart($devisAcceptesParMois),

            Stat::make('Facturé ce mois', new HtmlString("<span style='font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em;'>" . number_format($facture_ce_mois, 0, ',', ' ') . " DH</span>"))
                ->description($facture_ce_mois >= $facture_mois_dernier ? 'En hausse par rapport au mois dernier' : 'En baisse par rapport au mois dernier')
                ->color($facture_ce_mois >= $facture_mois_dernier ? 'success' : 'danger')
                ->chart($factureParMois),

            Stat::make('Campagnes en cours', new HtmlString("<span style='font-size: 1.45rem; font-weight: 700; letter-spacing: -0.02em;'>{$campagnesEnCours}</span>"))
                ->description('Actives actuellement')
                ->color('info')
                ->chart($campagnesParMois),

            Stat::make('Factures en retard', new HtmlString("<span style='font-size: 1.45rem; font-weight: 700; letter-spacing: -0.02em;'>{$facturesEnRetard}</span>"))
                ->description($facturesEnRetard > 0 ? 'Relances à effectuer' : 'Aucune facture en retard')
                ->color($facturesEnRetard > 0 ? 'danger' : 'success')
                ->chart($retardsParMois),
        ];
    }
}