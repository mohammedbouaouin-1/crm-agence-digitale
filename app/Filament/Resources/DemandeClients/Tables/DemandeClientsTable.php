<?php

namespace App\Filament\Resources\DemandeClients\Tables;

use App\Models\DemandeClient;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DemandeClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sujet')
                    ->label('Sujet')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(50),

                IconColumn::make('traite')
                    ->label('Traité')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('traite')
                    ->label('Statut de traitement')
                    ->placeholder('Toutes les demandes')
                    ->trueLabel('Traitées uniquement')
                    ->falseLabel('Non traitées uniquement'),
            ])
            ->recordActions([
                Action::make('marquer_traite')
                    ->label('Marquer traitée')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (DemandeClient $record) => ! $record->traite)
                    ->action(function (DemandeClient $record) {
                        $record->update(['traite' => true]);

                        Notification::make()
                            ->title('Demande marquée comme traitée')
                            ->success()
                            ->send();
                    }),
                Action::make('marquer_non_traite')
                    ->label('Rouvrir')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->visible(fn (DemandeClient $record) => $record->traite)
                    ->action(function (DemandeClient $record) {
                        $record->update(['traite' => false]);

                        Notification::make()
                            ->title('Demande rouverte')
                            ->info()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
