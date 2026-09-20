<?php

namespace App\Filament\Resources\Campagnes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CampagneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations de la Campagne')
                ->description('Client concerné et caractéristiques principales')
                ->icon('heroicon-o-megaphone')
                ->columns(2)
                ->schema([
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('devis_id', null))
                        ->required(),

                    Select::make('devis_id')
                        ->label('Devis d\'origine (optionnel)')
                        ->relationship('devis', 'numero', function ($query, $get) {
                            $clientId = $get('client_id');
                            return $clientId ? $query->where('client_id', $clientId) : $query;
                        })
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero} — {$record->titre} (" . number_format($record->montant, 0, ',', ' ') . " DH)")
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $devis = \App\Models\Devis::find($state);
                                if ($devis) {
                                    $set('budget', $devis->montant);
                                }
                            }
                        }),

                    TextInput::make('nom')
                        ->label('Nom de la campagne')
                        ->placeholder('ex: Boost Promo Été 2026')
                        ->required(),

                    TextInput::make('compte_cible')
                        ->label('Compte / Page ciblée')
                        ->placeholder('ex: @maboutique ou Resto Gourmet'),

                    TextInput::make('url_cible')
                        ->label('Lien / URL à sponsoriser')
                        ->placeholder('https://instagram.com/nom_du_compte')
                        ->prefixIcon('heroicon-o-link'),

                    Select::make('type')
                        ->label('Type de prestation')
                        ->options([
                            'SEO' => 'SEO',
                            'Ads' => 'Pub Ads',
                        ])
                        ->required(),

                    TextInput::make('plateforme')
                        ->label('Plateforme cible')
                        ->placeholder('ex: Instagram, TikTok, Meta Ads, Google')
                        ->columnSpanFull(),
                ]),

            Section::make('Budget & Planning')
                ->description('Montant alloué et dates de diffusion')
                ->icon('heroicon-o-calendar')
                ->columns(3)
                ->schema([
                    TextInput::make('budget')
                        ->label('Budget alloué')
                        ->numeric()
                        ->prefix('DH')
                        ->helperText('Rempli automatiquement si un devis est sélectionné, ou saisie libre.'),

                    DatePicker::make('date_debut')
                        ->label('Date de début')
                        ->default(now())
                        ->required(),

                    DatePicker::make('date_fin')
                        ->label('Date de fin'),

                    Select::make('statut')
                        ->label('Statut')
                        ->options([
                            'en_cours' => 'En cours',
                            'en_pause' => 'En pause',
                            'terminee' => 'Terminée',
                        ])
                        ->default('en_cours')
                        ->required()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}