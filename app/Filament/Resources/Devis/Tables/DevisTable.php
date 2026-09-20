<?php

namespace App\Filament\Resources\Devis\Tables;

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
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ') . ' DH')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepte'   => 'success',
                        'envoye'    => 'info',
                        'brouillon' => 'secondary',
                        'refuse'    => 'danger',
                        'expire'    => 'warning',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accepte'   => 'Accepté',
                        'envoye'    => 'Envoyé',
                        'brouillon' => 'Brouillon',
                        'refuse'    => 'Refusé',
                        'expire'    => 'Expiré',
                        default     => ucfirst($state),
                    }),

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
                        'envoye'    => 'Envoyé',
                        'accepte'   => 'Accepté',
                        'refuse'    => 'Refusé',
                        'expire'    => 'Expiré',
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

                Action::make('creer_projet')
                    ->label('Créer le projet')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->visible(fn (Devis $record) => $record->statut === 'accepte')
                    ->form([
                        TextInput::make('nom')
                            ->label('Nom du projet')
                            ->default(fn (Devis $record) => $record->titre)
                            ->required(),
                        Select::make('type_site')
                            ->label('Type de site')
                            ->options([
                                'vitrine'         => 'Site vitrine',
                                'e-commerce'      => 'E-commerce',
                                'application_web' => 'Application web',
                                'refonte'         => 'Refonte de site existant',
                            ])
                            ->default('vitrine')
                            ->required(),
                        TextInput::make('budget')
                            ->label('Budget hérité du devis (DH)')
                            ->default(fn (Devis $record) => $record->montant)
                            ->disabled()
                            ->dehydrated(false),
                        DatePicker::make('date_debut')
                            ->label('Date de démarrage')
                            ->default(now())
                            ->required(),
                        DatePicker::make('date_livraison_prevue')
                            ->label('Date de livraison prévue')
                            ->default(now()->addDays(30)),
                    ])
                    ->action(function (Devis $record, array $data) {
                        Projet::create([
                            'client_id'             => $record->client_id,
                            'devis_id'              => $record->id,
                            'nom'                   => $data['nom'],
                            'type_site'             => $data['type_site'],
                            'budget'                => $record->montant,
                            'date_debut'            => $data['date_debut'],
                            'date_livraison_prevue' => $data['date_livraison_prevue'] ?? null,
                            'statut'                => 'maquette',
                        ]);

                        Notification::make()
                            ->title('Projet créé avec succès')
                            ->body("Le projet a été créé avec un budget de " . number_format($record->montant, 2, ',', ' ') . " DH.")
                            ->success()
                            ->send();
                    }),

                Action::make('creer_campagne')
                    ->label('Créer campagne')
                    ->icon('heroicon-o-megaphone')
                    ->color('warning')
                    ->visible(fn (Devis $record) => $record->statut === 'accepte')
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
                            ->label('Budget hérité du devis (DH)')
                            ->default(fn (Devis $record) => $record->montant)
                            ->disabled()
                            ->dehydrated(false),
                        DatePicker::make('date_debut')
                            ->label('Date de démarrage')
                            ->default(now())
                            ->required(),
                        DatePicker::make('date_fin')
                            ->label('Date de fin prévue')
                            ->default(now()->addDays(30)),
                    ])
                    ->action(function (Devis $record, array $data) {
                        Campagne::create([
                            'client_id'  => $record->client_id,
                            'devis_id'   => $record->id,
                            'nom'        => $data['nom'],
                            'type'       => $data['type'],
                            'plateforme' => $data['plateforme'] ?? null,
                            'budget'     => $record->montant,
                            'date_debut' => $data['date_debut'],
                            'date_fin'   => $data['date_fin'] ?? null,
                            'statut'     => 'en_cours',
                        ]);

                        Notification::make()
                            ->title('Campagne créée avec succès')
                            ->body("La campagne a été créée avec un budget de " . number_format($record->montant, 2, ',', ' ') . " DH.")
                            ->success()
                            ->send();
                    }),

                Action::make('generer_facture')
                    ->label('Créer facture')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (Devis $record) => $record->statut === 'accepte')
                    ->form([
                        Select::make('type_facture')
                            ->label('Type de facturation')
                            ->options([
                                'acompte_30' => 'Acompte de 30%',
                                'acompte_50' => 'Acompte de 50%',
                                'solde_100'  => 'Totalité (100%)',
                            ])
                            ->default('acompte_30')
                            ->required(),
                        DatePicker::make('date_emission')
                            ->label('Date d\'émission')
                            ->default(now())
                            ->required(),
                        DatePicker::make('date_echeance')
                            ->label('Date d\'échéance')
                            ->default(now()->addDays(15))
                            ->required(),
                    ])
                    ->action(function (Devis $record, array $data) {
                        $taux = match ($data['type_facture']) {
                            'acompte_30' => 0.30,
                            'acompte_50' => 0.50,
                            default      => 1.00,
                        };

                        $montantFacture = round($record->montant * $taux, 2);

                        // Calcul du prochain numéro de facture
                        $annee = date('Y');
                        $derniere = Facture::where('numero', 'like', "FAC-{$annee}-%")
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($derniere && preg_match('/FAC-\d{4}-(\d+)/', $derniere->numero, $matches)) {
                            $prochain = (int) $matches[1] + 1;
                        } else {
                            $prochain = 1;
                        }
                        $numero = sprintf('FAC-%s-%04d', $annee, $prochain);

                        Facture::create([
                            'client_id'     => $record->client_id,
                            'devis_id'      => $record->id,
                            'numero'        => $numero,
                            'montant'       => $montantFacture,
                            'date_emission' => $data['date_emission'],
                            'date_echeance' => $data['date_echeance'],
                            'statut'        => 'en_attente',
                        ]);

                        Notification::make()
                            ->title('Facture générée avec succès')
                            ->body("Facture {$numero} émise pour un montant de " . number_format($montantFacture, 2, ',', ' ') . " DH.")
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
