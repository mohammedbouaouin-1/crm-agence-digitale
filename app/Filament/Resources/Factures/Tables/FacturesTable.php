<?php

namespace App\Filament\Resources\Factures\Tables;

use App\Mail\NouvelleFactureDisponible;
use App\Mail\RelanceFacture;
use App\Models\Facture;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->weight('bold')
                    ->description(function (Facture $record) {
                        $totalPaye = (float) $record->paiements->sum('montant');
                        $reste = max(0, (float) $record->montant - $totalPaye);

                        if ($record->statut === 'payee' || $reste <= 0) {
                            return 'Soldée à 100%';
                        }

                        return 'Reste dû : '.number_format($reste, 2, ',', ' ').' DH';
                    }),

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
                Action::make('enregistrer_paiement')
                    ->label('+ Règlement')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Facture $record) => $record->statut !== 'payee')
                    ->form([
                        TextInput::make('montant')
                            ->label('Montant versé (DH)')
                            ->numeric()
                            ->required()
                            ->default(fn (Facture $record) => max(0, (float) $record->montant - (float) $record->paiements->sum('montant'))),
                        DatePicker::make('date')
                            ->label('Date d\'encaissement')
                            ->required()
                            ->default(now()),
                        Select::make('methode')
                            ->label('Mode de règlement')
                            ->required()
                            ->options([
                                'virement' => 'Virement bancaire',
                                'cheque' => 'Chèque',
                                'especes' => 'Espèces',
                                'carte' => 'Carte bancaire',
                            ])
                            ->default('virement'),
                    ])
                    ->action(function (Facture $record, array $data) {
                        $record->paiements()->create($data);

                        Notification::make()
                            ->title('Règlement enregistré')
                            ->body('Le versement a été enregistré et le statut de la facture a été actualisé.')
                            ->success()
                            ->send();
                    }),

                Action::make('envoyer_email')
                    ->label('Envoyer')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn (Facture $record) => ! empty($record->client?->email))
                    ->requiresConfirmation()
                    ->modalHeading('Transmettre la facture au client')
                    ->modalDescription(fn (Facture $record) => "Envoyer la facture {$record->numero} et son PDF par email à {$record->client->nom} ({$record->client->email}) ?")
                    ->modalSubmitActionLabel('Envoyer par email')
                    ->action(function (Facture $record) {
                        Mail::to($record->client->email)->send(new NouvelleFactureDisponible($record));

                        Notification::make()
                            ->title('Facture envoyée')
                            ->body("La facture {$record->numero} a été transmise à {$record->client->email}.")
                            ->success()
                            ->send();
                    }),

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
