<?php

namespace App\Filament\Client\Widgets;

use App\Models\Projet;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MesProjets extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): ?string
    {
        return 'Mes projets web';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Projet::query()->where('client_id', auth()->user()?->client_id)
            )
            ->columns([
                TextColumn::make('nom')
                    ->label('Projet')
                    ->weight('bold'),

                TextColumn::make('type_site')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'vitrine' => 'Site vitrine',
                        'e-commerce' => 'E-commerce',
                        'application_web' => 'Application web',
                        'refonte' => 'Refonte',
                        default => $state,
                    }),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'maquette' => 'secondary',
                        'developpement' => 'info',
                        'tests' => 'warning',
                        'livre' => 'success',
                        'en_pause' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'maquette' => 'Maquette',
                        'developpement' => 'Développement',
                        'tests' => 'Tests',
                        'livre' => 'Livré',
                        'en_pause' => 'En pause',
                        default => $state,
                    }),

                TextColumn::make('progression')
                    ->label('Avancement')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 100 => 'success',
                        $state >= 50 => 'info',
                        $state > 0 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (int $state): string => "{$state}%"),

                TextColumn::make('date_livraison_prevue')
                    ->label('Livraison prévue')
                    ->date('d/m/Y'),
            ]);
    }
}
