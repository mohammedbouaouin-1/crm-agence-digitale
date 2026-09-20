<?php

namespace App\Filament\Resources\Devis\Schemas;

use App\Models\Devis;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DevisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations du devis')
                ->description('Client et objet de la proposition commerciale')
                ->icon('heroicon-o-document-text')
                ->columns(2)
                ->schema([
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('numero')
                        ->label('N° de Devis')
                        ->default(fn () => Devis::genererNumero())
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('titre')
                        ->label('Objet / Titre du devis')
                        ->placeholder('ex. Création de site e-commerce et stratégie Ads')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            Section::make('Montant et Validité')
                ->description('Tarification et période de validité de l\'offre')
                ->icon('heroicon-o-currency-dollar')
                ->columns(3)
                ->schema([
                    TextInput::make('montant')
                        ->label('Montant Net (DH)')
                        ->numeric()
                        ->prefix('DH')
                        ->required(),

                    DatePicker::make('date_emission')
                        ->label('Date d\'émission')
                        ->default(now())
                        ->required(),

                    DatePicker::make('date_validite')
                        ->label('Date de validité')
                        ->default(now()->addDays(30))
                        ->required(),

                    Select::make('statut')
                        ->label('Statut')
                        ->options([
                            'brouillon' => 'Brouillon',
                            'envoye'    => 'Envoyé au client',
                            'accepte'   => 'Accepté',
                            'refuse'    => 'Refusé',
                            'expire'    => 'Expiré',
                        ])
                        ->default('brouillon')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Prestations & Modalités')
                ->description('Détail des livrables et modalités de règlement')
                ->icon('heroicon-o-clipboard-document-list')
                ->columns(1)
                ->schema([
                    Textarea::make('description')
                        ->label('Description détaillée des prestations')
                        ->placeholder('Décrivez les fonctionnalités, livrables techniques, maquettes, etc.')
                        ->rows(4),

                    Textarea::make('conditions')
                        ->label('Conditions de règlement')
                        ->placeholder('ex. Acompte de 30% au démarrage, solde de 70% à la livraison.')
                        ->rows(2),
                ]),
        ]);
    }
}
