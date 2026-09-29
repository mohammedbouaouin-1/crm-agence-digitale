<?php

namespace App\Filament\Resources\Paiements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                    ->icon('heroicon-o-document-text')
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
                    ->icon(fn (string $state): string => match ($state) {
                        'virement' => 'heroicon-o-arrows-right-left',
                        'carte' => 'heroicon-o-credit-card',
                        'cheque' => 'heroicon-o-document-check',
                        'especes' => 'heroicon-o-banknotes',
                        default => 'heroicon-o-currency-dollar',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'virement' => 'success',
                        'cheque' => 'warning',
                        'especes' => 'info',
                        'carte' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'virement' => 'Virement',
                        'cheque' => 'Chèque',
                        'especes' => 'Espèces',
                        'carte' => 'Carte bancaire',
                        default => $state,
                    }),

                TextColumn::make('date')
                    ->label('Date de paiement')
                    ->date('d/m/Y')
                    ->icon('heroicon-o-calendar')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('methode')
                    ->label('Méthode de paiement')
                    ->options([
                        'virement' => 'Virement',
                        'cheque' => 'Chèque',
                        'especes' => 'Espèces',
                        'carte' => 'Carte bancaire',
                    ]),

                Filter::make('periode')
                    ->label('Période de paiement')
                    ->form([
                        DatePicker::make('date_debut')->label('Du'),
                        DatePicker::make('date_fin')->label('Au'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_debut'], fn ($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['date_fin'], fn ($q, $date) => $q->whereDate('date', '<=', $date));
                    }),
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
