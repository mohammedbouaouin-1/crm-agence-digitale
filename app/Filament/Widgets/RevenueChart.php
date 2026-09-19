<?php

namespace App\Filament\Widgets;

use App\Models\Facture;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Chiffre d\'affaires facturé (6 derniers mois)';

    protected int|string|array $columnSpan = 'full';

    protected function getMaxHeight(): ?string
    {
        return '300px';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $mois = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i);
        });

        $labels = $mois->map(fn ($date) => $date->translatedFormat('M Y'));

        $donnees = $mois->map(function ($date) {
            return Facture::whereYear('date_emission', $date->year)
                ->whereMonth('date_emission', $date->month)
                ->sum('montant');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Montant facturé (DH)',
                    'data' => $donnees->toArray(),
                    'borderColor' => '#06b6d4',
                    'backgroundColor' => 'rgba(6, 182, 212, 0.12)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => '#06b6d4',
                    'pointBorderColor' => '#ffffff',
                    'pointHoverRadius' => 7,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}