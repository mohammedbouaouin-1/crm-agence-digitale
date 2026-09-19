<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations personnelles')
                ->description('Coordonnées du contact principal')
                ->icon('heroicon-o-user')
                ->columns(2)
                ->schema([
                    TextInput::make('nom')
                        ->label('Nom complet')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('entreprise')
                        ->label('Nom de la page / Marque')
                        ->placeholder('ex: @maboutique ou Nom de marque')
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('telephone')
                        ->label('Téléphone')
                        ->tel(),
                ]),

            Section::make('Détails & Liens')
                ->description('Page principale, secteur et statut')
                ->icon('heroicon-o-briefcase')
                ->columns(2)
                ->schema([
                    TextInput::make('adresse')
                        ->label('Lien / URL de la page')
                        ->placeholder('https://instagram.com/nom_du_compte')
                        ->prefixIcon('heroicon-o-link')
                        ->columnSpanFull(),

                    TextInput::make('secteur_activite')
                        ->label('Secteur d\'activité')
                        ->placeholder('ex: E-commerce, Restauration, Mode...'),

                    Select::make('statut')
                        ->label('Statut')
                        ->options([
                            'prospect' => 'Prospect',
                            'actif'    => 'Actif',
                            'inactif'  => 'Inactif',
                        ])
                        ->required(),
                ]),
        ]);
    }
}