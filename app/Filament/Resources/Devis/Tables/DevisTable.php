<?php

namespace App\Filament\Resources\Devis\Tables;

use App\Filament\Resources\Campagnes\CampagneResource;
use App\Filament\Resources\Factures\FactureResource;
use App\Filament\Resources\Projets\ProjetResource;
use App\Mail\NouveauDevisDisponible;
use App\Models\Campagne;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Projet;
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

class DevisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Devis')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('titre')
                    ->label('Objet')
                    ->searchable()
                    ->description(function (Devis $record) {
                        $elements = [];
                        if ($projet = $record->projets->first()) {
                            $elements[] = 'Projet : '.$projet->nom;
                        }
                        if ($campagne = $record->campagnes->first()) {
                            $elements[] = 'Campagne : '.$campagne->nom;
                        }

                        return ! empty($elements) ? implode(' | ', $elements) : null;
                    }),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ').' DH')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepte' => 'success',
                        'envoye' => 'info',
                        'brouillon' => 'secondary',
                        'refuse' => 'danger',
                        'expire' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accepte' => 'Accepté',
                        'envoye' => 'Envoyé',
                        'brouillon' => 'Brouillon',
                        'refuse' => 'Refusé',
                        'expire' => 'Expiré',
                        default => ucfirst($state),
                    })
                    ->description(fn (Devis $record) => $record->accepte_le ? 'Validé le '.$record->accepte_le->format('d/m/Y H:i') : null),

                TextColumn::make('date_emission')
                    ->label('Émission')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_validite')
                    ->label('Validité')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->date_validite?->isPast() && $record->statut !== 'accepte' ? 'danger' : null),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'brouillon' => 'Brouillon',
                        'envoye' => 'Envoyé',
                        'accepte' => 'Accepté',
                        'refuse' => 'Refusé',
                        'expire' => 'Expiré',
                    ]),
                SelectFilter::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'nom'),
            ])
            ->recordActions([
                Action::make('telecharger_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Devis $record) => route('devis.pdf', $record))
                    ->openUrlInNewTab(),

                Action::make('envoyer_email')
                    ->label('Envoyer au client')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn (Devis $record) => ! empty($record->client?->email) && in_array($record->statut, ['brouillon', 'envoye']))
                    ->requiresConfirmation()
                    ->modalHeading('Envoyer la proposition commerciale')
                    ->modalDescription(fn (Devis $record) => "Transmettre le devis {$record->numero} et son PDF par email à {$record->client->nom} ({$record->client->email}) ?")
                    ->modalSubmitActionLabel('Envoyer par email')
                    ->action(function (Devis $record) {
                        Mail::to($record->client->email)->send(new NouveauDevisDisponible($record));

                        if ($record->statut === 'brouillon') {
                            $record->update(['statut' => 'envoye']);
                        }

                        Notification::make()
                            ->title('Devis envoyé par email')
                            ->body("La proposition {$record->numero} a été transmise à {$record->client->email}.")
                            ->success()
                            ->send();
                    }),

                Action::make('creer_projet')
                    ->label('Créer le projet')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->visible(fn (Devis $record) => $record->statut === 'accepte' && $record->projets->isEmpty())
                    ->form([
                        TextInput::make('nom')
                            ->label('Nom du projet')
                            ->default(fn (Devis $record) => $record->titre)
                            ->required(),
                        Select::make('type_site')
                            ->label('Type de site')
                            ->options([
                                'vitrine' => 'Site vitrine',
                                'e-commerce' => 'E-commerce',
                                'application_web' => 'Application web',
                                'refonte' => 'Refonte de site existant',
                            ])
                            ->default('vitrine')
                            ->required(),
                        TextInput::make('budget')
                            ->label('Budget alloué au projet (DH)')
                            ->numeric()
                            ->default(fn (Devis $record) => $record->montant)
                            ->helperText('Ajustable si le devis inclut à la fois un site et du marketing / SEO.')
                            ->required(),
                        DatePicker::make('date_debut')
                            ->label('Date de démarrage')
                            ->default(now())
                            ->required(),
                        DatePicker::make('date_livraison_prevue')
                            ->label('Date de livraison prévue')
                            ->default(now()->addDays(30)),
                    ])
                    ->action(function (Devis $record, array $data) {
                        $budget = (float) ($data['budget'] ?? $record->montant);
                        Projet::create([
                            'client_id' => $record->client_id,
                            'devis_id' => $record->id,
                            'nom' => $data['nom'],
                            'type_site' => $data['type_site'],
                            'budget' => $budget,
                            'date_debut' => $data['date_debut'],
                            'date_livraison_prevue' => $data['date_livraison_prevue'] ?? null,
                            'statut' => 'maquette',
                        ]);

                        Notification::make()
                            ->title('Projet créé avec succès')
                            ->body('Le projet a été créé avec un budget de '.number_format($budget, 2, ',', ' ').' DH.')
                            ->success()
                            ->send();
                    }),

                Action::make('voir_projet')
                    ->label('Voir projet')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Devis $record) => $record->projets->isNotEmpty())
                    ->url(fn (Devis $record) => ProjetResource::getUrl('edit', ['record' => $record->projets->first()])),

                Action::make('creer_campagne')
                    ->label('Créer campagne')
                    ->icon('heroicon-o-megaphone')
                    ->color('warning')
                    ->visible(fn (Devis $record) => $record->statut === 'accepte' && $record->campagnes->isEmpty())
                    ->form([
                        TextInput::make('nom')
                            ->label('Nom de la campagne')
                            ->default(fn (Devis $record) => $record->titre)
                            ->required(),
                        Select::make('type')
                            ->label('Type de prestation')
                            ->options([
                                'Ads' => 'Pub Ads',
                                'SEO' => 'SEO',
                            ])
                            ->default('Ads')
                            ->required(),
                        TextInput::make('plateforme')
                            ->label('Plateforme cible')
                            ->placeholder('ex: Meta Ads, Google Ads, TikTok')
                            ->default('Meta Ads'),
                        TextInput::make('budget')
                            ->label('Budget alloué à la campagne (DH)')
                            ->numeric()
                            ->default(fn (Devis $record) => $record->montant)
                            ->helperText('Ajustable si le devis inclut à la fois un site et du marketing / SEO.')
                            ->required(),
                        DatePicker::make('date_debut')
                            ->label('Date de démarrage')
                            ->default(now())
                            ->required(),
                        DatePicker::make('date_fin')
                            ->label('Date de fin prévue')
                            ->default(now()->addDays(30)),
                    ])
                    ->action(function (Devis $record, array $data) {
                        $budget = (float) ($data['budget'] ?? $record->montant);
                        Campagne::create([
                            'client_id' => $record->client_id,
                            'devis_id' => $record->id,
                            'nom' => $data['nom'],
                            'type' => $data['type'],
                            'plateforme' => $data['plateforme'] ?? null,
                            'budget' => $budget,
                            'date_debut' => $data['date_debut'],
                            'date_fin' => $data['date_fin'] ?? null,
                            'statut' => 'en_cours',
                        ]);

                        Notification::make()
                            ->title('Campagne créée avec succès')
                            ->body('La campagne a été créée avec un budget de '.number_format($budget, 2, ',', ' ').' DH.')
                            ->success()
                            ->send();
                    }),

                Action::make('voir_campagne')
                    ->label('Voir campagne')
                    ->icon('heroicon-o-check-circle')
                    ->color('warning')
                    ->visible(fn (Devis $record) => $record->campagnes->isNotEmpty())
                    ->url(fn (Devis $record) => CampagneResource::getUrl('edit', ['record' => $record->campagnes->first()])),

                Action::make('generer_facture')
                    ->label('Créer facture')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (Devis $record) => $record->statut === 'accepte' && $record->factures->isEmpty())
                    ->modalHeading('Générer la facture du devis')
                    ->modalDescription(fn (Devis $record) => "Émettre la facture pour la totalité du devis {$record->numero} (".number_format($record->montant, 2, ',', ' ').' DH). Les règlements (acompte et solde) seront gérés dans le module Paiements.')
                    ->modalSubmitActionLabel('Créer la facture')
                    ->form([
                        DatePicker::make('date_emission')
                            ->label('Date d\'émission')
                            ->default(now())
                            ->required(),
                        DatePicker::make('date_echeance')
                            ->label('Date d\'échéance')
                            ->default(now()->addDays(30))
                            ->required(),
                    ])
                    ->action(function (Devis $record, array $data) {
                        $numero = Facture::genererNumero();

                        Facture::create([
                            'client_id' => $record->client_id,
                            'devis_id' => $record->id,
                            'numero' => $numero,
                            'montant' => $record->montant,
                            'date_emission' => $data['date_emission'],
                            'date_echeance' => $data['date_echeance'],
                            'statut' => 'en_attente',
                        ]);

                        Notification::make()
                            ->title('Facture créée avec succès')
                            ->body("La facture {$numero} a été générée pour le montant total de ".number_format($record->montant, 2, ',', ' ').' DH.')
                            ->success()
                            ->send();
                    }),

                Action::make('voir_facture')
                    ->label('Voir facture')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->visible(fn (Devis $record) => $record->factures->isNotEmpty())
                    ->url(fn (Devis $record) => FactureResource::getUrl('edit', ['record' => $record->factures->first()])),

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
