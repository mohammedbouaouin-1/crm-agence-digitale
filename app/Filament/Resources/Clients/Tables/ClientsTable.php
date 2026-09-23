<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Mail\AccesClientCree;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('entreprise')
                    ->label('Page / Marque')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copié !'),

                TextColumn::make('telephone')
                    ->placeholder('—'),

                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'actif' => 'success',
                        'prospect' => 'warning',
                        'inactif' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'actif' => 'Actif',
                        'prospect' => 'Prospect',
                        'inactif' => 'Inactif',
                        default => $state,
                    }),

                TextColumn::make('campagnes_count')
                    ->counts('campagnes')
                    ->label('Campagnes')
                    ->badge()
                    ->color('info')
                    ->tooltip(function ($record) {
                        $noms = $record->campagnes->pluck('nom')->filter();

                        return $noms->count() > 0
                            ? 'Campagnes: '.$noms->join(', ')
                            : 'Aucune campagne';
                    }),

                TextColumn::make('total_facture')
                    ->label('CA Facturé')
                    ->state(fn ($record) => $record->factures->sum('montant'))
                    ->formatStateUsing(fn ($state) => $state > 0 ? number_format($state, 2, ',', ' ').' DH' : '—')
                    ->weight('bold')
                    ->description(function ($record) {
                        $factures = $record->factures;
                        if ($factures->isEmpty()) {
                            return null;
                        }

                        $totalFacture = (float) $factures->sum('montant');
                        $totalPaye = (float) $factures->sum(fn ($f) => $f->paiements->sum('montant'));
                        $reste = max(0, $totalFacture - $totalPaye);

                        if ($reste <= 0) {
                            return 'Soldé (0 DH dû)';
                        }

                        $hasRetard = $factures->where('statut', 'en_retard')->isNotEmpty();

                        return ($hasRetard ? 'Impayé en retard : ' : 'Encours : ').number_format($reste, 2, ',', ' ').' DH';
                    })
                    ->descriptionColor(function ($record) {
                        $factures = $record->factures;
                        if ($factures->isEmpty()) {
                            return null;
                        }

                        $totalFacture = (float) $factures->sum('montant');
                        $totalPaye = (float) $factures->sum(fn ($f) => $f->paiements->sum('montant'));
                        $reste = max(0, $totalFacture - $totalPaye);

                        if ($reste <= 0) {
                            return 'success';
                        }

                        return $factures->where('statut', 'en_retard')->isNotEmpty() ? 'danger' : 'warning';
                    }),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options([
                        'prospect' => 'Prospect',
                        'actif' => 'Actif',
                        'inactif' => 'Inactif',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('creer_acces')
                    ->label('Créer un accès')
                    ->icon('heroicon-o-key')
                    ->visible(fn ($record) => ! User::where('client_id', $record->id)->exists())
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $motDePasse = Str::password(12);

                        User::create([
                            'name' => $record->nom,
                            'email' => $record->email,
                            'password' => bcrypt($motDePasse),
                            'client_id' => $record->id,
                        ]);

                        Mail::to($record->email)->send(
                            new AccesClientCree($record->nom, $record->email, $motDePasse)
                        );

                        Notification::make()
                            ->title('Accès créé et email envoyé')
                            ->success()
                            ->send();
                    }),
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
