<?php

namespace App\Filament\Resources\Campagnes\Tables;

use App\Models\Campagne;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CampagnesTable
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
                    ->label('Nom de la campagne')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Campagne $record) => $record->devis ? 'Devis: ' . $record->devis->numero : null),

                TextColumn::make('compte_cible')
                    ->label('Compte / Page')
                    ->searchable()
                    ->placeholder('—')
                    ->url(fn ($record) => $record->url_cible ?: null, shouldOpenInNewTab: true)
                    ->icon(fn ($record) => $record->url_cible ? 'heroicon-o-arrow-top-right-on-square' : null),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SEO'          => 'success',
                        'Ads'          => 'info',
                        default        => 'gray',
                    }),

                TextColumn::make('plateforme')
                    ->label('Plateforme')
                    ->placeholder('—'),

                TextColumn::make('budget')
                    ->label('Budget')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2, ',', ' ') . ' DH' : '—')
                    ->sortable(),

                TextColumn::make('date_debut')
                    ->label('Début')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('En cours'),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en_cours' => 'success',
                        'en_pause' => 'warning',
                        'terminee' => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'en_cours' => 'En cours',
                        'en_pause' => 'En pause',
                        'terminee' => 'Terminée',
                        default    => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_cours' => 'En cours',
                        'en_pause' => 'En pause',
                        'terminee' => 'Terminée',
                    ]),
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'SEO' => 'SEO',
                        'Ads' => 'Ads',
                    ]),
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
