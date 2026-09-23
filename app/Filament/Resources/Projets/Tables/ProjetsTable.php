<?php

namespace App\Filament\Resources\Projets\Tables;

use App\Models\Projet;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nom')
                    ->label('Nom du projet')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Projet $record) => $record->devis ? 'Devis: '.$record->devis->numero : null),

                TextColumn::make('type_site')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vitrine' => 'info',
                        'e-commerce' => 'success',
                        'application_web' => 'primary',
                        'refonte' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'vitrine' => 'Site vitrine',
                        'e-commerce' => 'E-commerce',
                        'application_web' => 'Application web',
                        'refonte' => 'Refonte',
                        default => $state,
                    }),

                TextColumn::make('budget')
                    ->label('Budget')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2, ',', ' ').' DH' : '—')
                    ->sortable(),

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
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->date_livraison_prevue?->isPast() && $record->statut !== 'livre' ? 'danger' : null)
                    ->description(fn ($record) => $record->date_livraison_prevue?->isPast() && $record->statut !== 'livre' ? 'Délai dépassé' : null)
                    ->descriptionColor('danger'),

                TextColumn::make('url_site')
                    ->label('Lien')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => $record->url_site, shouldOpenInNewTab: true)
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'maquette' => 'Maquette',
                        'developpement' => 'Développement',
                        'tests' => 'Tests',
                        'livre' => 'Livré',
                        'en_pause' => 'En pause',
                    ]),
                Filter::make('en_retard')
                    ->label('En retard de livraison')
                    ->query(fn ($query) => $query->where('date_livraison_prevue', '<', now())->where('statut', '!=', 'livre')),
                SelectFilter::make('type_site')
                    ->label('Type de site')
                    ->options([
                        'vitrine' => 'Site vitrine',
                        'e-commerce' => 'E-commerce',
                        'application_web' => 'Application web',
                        'refonte' => 'Refonte',
                    ]),
                SelectFilter::make('client_id')
                    ->relationship('client', 'nom')
                    ->label('Client'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
