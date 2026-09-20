<?php

namespace App\Filament\Client\Widgets;

use App\Models\Campagne;
use App\Models\Facture;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class ClientStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $clientId = auth()->user()?->client_id;

        if (! $clientId) {
            return [];
        }

        $campagnesEnCours = Campagne::where('client_id', $clientId)
            ->where('statut', 'en_cours')
            ->count();

        $facturesEnAttente = Facture::where('client_id', $clientId)
            ->where('statut', '!=', 'payee')
            ->count();

        $totalFacture = Facture::where('client_id', $clientId)
            ->sum('montant');

        // Données des 6 derniers mois
        $facturesParMois = collect(range(5, 0))->map(function ($monthsAgo) use ($clientId) {
            $date = now()->subMonths($monthsAgo);

            return (float) Facture::where('client_id', $clientId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('montant');
        })->toArray();

        $campagnesParMois = collect(range(5, 0))->map(function ($monthsAgo) use ($clientId) {
            $date = now()->subMonths($monthsAgo);

            return (int) Campagne::where('client_id', $clientId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        })->toArray();

        $facturesAttenteParMois = collect(range(5, 0))->map(function ($monthsAgo) use ($clientId) {
            $date = now()->subMonths($monthsAgo);

            return (int) Facture::where('client_id', $clientId)
                ->where('statut', '!=', 'payee')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        })->toArray();

        return [
            Stat::make('Campagnes en cours', new HtmlString("<span style='font-size: 1.45rem; font-weight: 700; letter-spacing: -0.02em;'>{$campagnesEnCours}</span>"))
                ->description('Campagnes marketing actives')
                ->chart($campagnesParMois)
                ->color('info'),

            Stat::make('Factures en attente', new HtmlString("<span style='font-size: 1.45rem; font-weight: 700; letter-spacing: -0.02em;'>{$facturesEnAttente}</span>"))
                ->description($facturesEnAttente > 0 ? 'Factures à régler' : 'Toutes les factures à jour')
                ->chart($facturesAttenteParMois)
                ->color($facturesEnAttente > 0 ? 'warning' : 'success'),

            Stat::make('Total facturé', new HtmlString("<span style='font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em;'>".number_format($totalFacture, 0, ',', ' ').' DH</span>'))
                ->description('Cumul de vos prestations')
                ->chart($facturesParMois)
                ->color('success'),
        ];
    }
}
