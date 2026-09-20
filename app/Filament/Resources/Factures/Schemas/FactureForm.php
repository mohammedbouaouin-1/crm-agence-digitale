<?php

namespace App\Filament\Resources\Factures\Schemas;

use App\Models\Devis;
use App\Models\Facture;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FactureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations de la facture')
                ->description('Client et numérotation')
                ->icon('heroicon-o-document-text')
                ->columns(3)
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
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->numero} — {$record->titre} (".number_format($record->montant, 0, ',', ' ').' DH)')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $devis = Devis::find($state);
                                if ($devis) {
                                    $set('montant', $devis->montant);
                                }
                            }
                        }),

                    TextInput::make('numero')
                        ->label('N° de Facture')
                        ->default(function () {
                            $annee = date('Y');
                            $derniere = Facture::where('numero', 'like', "FAC-{$annee}-%")
                                ->orderBy('id', 'desc')
                                ->first();

                            if ($derniere && preg_match('/FAC-\d{4}-(\d+)/', $derniere->numero, $matches)) {
                                $prochain = (int) $matches[1] + 1;
                            } else {
                                $prochain = 1;
                            }

                            return sprintf('FAC-%s-%04d', $annee, $prochain);
                        })
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),

            Section::make('Montant et Échéances')
                ->description('Règlement et suivi des dates')
                ->icon('heroicon-o-currency-dollar')
                ->columns(3)
                ->schema([
                    TextInput::make('montant')
                        ->label('Montant HT')
                        ->numeric()
                        ->prefix('DH')
                        ->required(),

                    DatePicker::make('date_emission')
                        ->label('Date d\'émission')
                        ->default(now())
                        ->required(),

                    DatePicker::make('date_echeance')
                        ->label('Date d\'échéance')
                        ->default(now()->addDays(30))
                        ->required(),

                    Select::make('statut')
                        ->label('Statut')
                        ->options([
                            'en_attente' => 'En attente',
                            'partiellement_payee' => 'Partiellement payée',
                            'payee' => 'Payée',
                            'en_retard' => 'En retard',
                        ])
                        ->default('en_attente')
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
