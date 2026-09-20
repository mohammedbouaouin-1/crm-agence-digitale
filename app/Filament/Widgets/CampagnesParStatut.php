<?php

namespace App\Filament\Widgets;

use App\Models\Campagne;
use Filament\Widgets\ChartWidget;

class CampagnesParStatut extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Campagnes par statut';

    protected int|string|array $columnSpan = 1;

    protected function getMaxHeight(): ?string
    {
        return '280px';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [
                        Campagne::where('statut', 'en_cours')->count(),
                        Campagne::where('statut', 'terminee')->count(),
                        Campagne::where('statut', 'en_pause')->count(),
                    ],
                    'backgroundColor' => ['#10b981', '#6b7280', '#f59e0b'],
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => ['En cours', 'Terminées', 'En pause'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
