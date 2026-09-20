<?php

namespace App\Filament\Resources\Projets\Schemas;

use App\Models\Devis;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations générales')
                ->description('Client et identité du projet web')
                ->icon('heroicon-o-computer-desktop')
                ->columns(2)
                ->schema([
                    Select::make('client_id')
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('devis_id', null))
                        ->required()
                        ->label('Client'),

                    Select::make('devis_id')
                        ->label('Devis d\'origine (optionnel)')
                        ->relationship('devis', 'numero', function ($query, $get) {
                            $clientId = $get('client_id');

                            return $clientId ? $query->where('client_id', $clientId) : $query;
                        })
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero} — {$record->titre} (".number_format($record->montant, 0, ',', ' ').' DH)')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $devis = Devis::find($state);
                                if ($devis) {
                                    $set('budget', $devis->montant);
                                }
                            }
                        }),

                    TextInput::make('nom')
                        ->label('Nom du projet')
                        ->required()
                        ->maxLength(255),

                    Select::make('type_site')
                        ->label('Type de site')
                        ->options([
                            'vitrine' => 'Site vitrine',
                            'e-commerce' => 'E-commerce',
                            'application_web' => 'Application web',
                            'refonte' => 'Refonte de site existant',
                        ])
                        ->required(),

                    TextInput::make('budget')
                        ->label('Budget')
                        ->numeric()
                        ->suffix('DH')
                        ->helperText('Rempli automatiquement si un devis est sélectionné, ou saisie libre.'),
                ]),

            Section::make('Planning & Statut')
                ->description('Délais de livraison et avancement')
                ->icon('heroicon-o-calendar')
                ->columns(3)
                ->schema([
                    DatePicker::make('date_debut')
                        ->label('Date de début')
                        ->required(),

                    DatePicker::make('date_livraison_prevue')
                        ->label('Livraison prévue'),

                    DatePicker::make('date_livraison_reelle')
                        ->label('Livraison réelle'),

                    Select::make('statut')
                        ->label('Statut du projet')
                        ->options([
                            'maquette' => 'Maquette',
                            'developpement' => 'Développement',
                            'tests' => 'Tests',
                            'livre' => 'Livré',
                            'en_pause' => 'En pause',
                        ])
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Accès Web')
                ->description('Nom de domaine et URL de démonstration')
                ->icon('heroicon-o-link')
                ->columns(2)
                ->schema([
                    TextInput::make('nom_domaine')
                        ->label('Nom de domaine')
                        ->placeholder('ex. client.ma'),

                    TextInput::make('url_site')
                        ->label('URL du site')
                        ->url()
                        ->placeholder('https://client.ma'),
                ]),
        ]);
    }
}
