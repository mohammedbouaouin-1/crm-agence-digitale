<?php

namespace App\Filament\Resources\Paiements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaiementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('facture.numero')
                    ->label('N° Facture')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('facture.client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ').' DH')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('methode')
                    ->label('Méthode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'virement' => 'success',
                        'cheque' => 'warning',
                        'especes' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'virement' => 'Virement',
                        'cheque' => 'Chèque',
                        'especes' => 'Espèces',
                        default => $state,
                    }),

                TextColumn::make('date')
                    ->label('Date de paiement')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('methode')
                    ->label('Méthode de paiement')
                    ->options([
                        'virement' => 'Virement',
                        'cheque' => 'Chèque',
                        'especes' => 'Espèces',
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
            ->defaultSort('date', 'desc');
    }
}
