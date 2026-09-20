<?php

namespace App\Filament\Resources\Factures\Tables;

use App\Mail\RelanceFacture;
use App\Models\Facture;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class FacturesTable
{
    public static function configure(Table $table): Table
    {
        Facture::where('date_echeance', '<', now())
            ->whereNotIn('statut', ['payee', 'en_retard'])
            ->update(['statut' => 'en_retard']);

        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Facture')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Facture $record) => $record->devis ? 'Devis: '.$record->devis->numero : null),

                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ').' DH')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'payee' => 'success',
                        'partiellement_payee' => 'warning',
                        'en_attente' => 'secondary',
                        'en_retard' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'payee' => 'Payée',
                        'partiellement_payee' => 'Partiellement payée',
                        'en_attente' => 'En attente',
                        'en_retard' => 'En retard',
                        default => $state,
                    }),

                TextColumn::make('date_emission')
                    ->label('Émission')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_echeance')
                    ->label('Échéance')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->date_echeance?->isPast() && $record->statut !== 'payee' ? 'danger' : null),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'partiellement_payee' => 'Partiellement payée',
                        'payee' => 'Payée',
                        'en_retard' => 'En retard',
                    ]),
            ])
            ->recordActions([
                Action::make('relancer')
                    ->label('Relancer')
                    ->icon('heroicon-o-envelope')
                    ->color('danger')
                    ->visible(fn ($record) => $record->statut === 'en_retard')
                    ->requiresConfirmation()
                    ->modalDescription(fn ($record) => "Envoyer un email de relance à {$record->client->nom} pour la facture {$record->numero} ?")
                    ->action(function ($record) {
                        if ($record->client?->email) {
                            Mail::to($record->client->email)->send(new RelanceFacture($record));

                            Notification::make()
                                ->title('Relance envoyée')
                                ->success()
                                ->send();
                        }
                    }),
                Action::make('telecharger_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Facture $record) => route('factures.pdf', $record))
                    ->openUrlInNewTab(),
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
