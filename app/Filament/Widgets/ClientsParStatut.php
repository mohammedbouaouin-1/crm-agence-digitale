<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientsParStatut extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Clients par statut';

    protected int|string|array $columnSpan = 1;

    protected function getMaxHeight(): ?string
    {
        return '280px';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive'          => true,
            'plugins'             => [
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
                        Client::where('statut', 'prospect')->count(),
                        Client::where('statut', 'actif')->count(),
                        Client::where('statut', 'inactif')->count(),
                    ],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#ef4444'],
                    'hoverOffset'     => 6,
                ],
            ],
            'labels' => ['Prospects', 'Actifs', 'Inactifs'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}