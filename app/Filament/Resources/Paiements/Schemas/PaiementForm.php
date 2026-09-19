<?php

namespace App\Filament\Resources\Paiements\Schemas;

use App\Models\Facture;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class PaiementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Enregistrement du paiement')
                ->description('Associer un paiement à une facture client')
                ->icon('heroicon-o-banknotes')
                ->columns(2)
                ->schema([
                    Select::make('facture_id')
                        ->label('Facture concernée')
                        ->relationship('facture', 'numero')
                        ->getOptionLabelFromRecordUsing(function (Facture $record) {
                            $clientNom = $record->client?->nom ?? 'Client';
                            $totalPaye = $record->totalPaye ?? 0;
                            $reste = max(0, $record->montant - $totalPaye);
                            return "{$record->numero} — {$clientNom} (Total: " . number_format($record->montant, 2, ',', ' ') . " DH | Reste: " . number_format($reste, 2, ',', ' ') . " DH)";
                        })
                        ->searchable(['numero'])
                        ->preload()
                        ->live()
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('montant_total_facture')
                        ->label('Montant total facture')
                        ->prefix('DH')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($get) => filled($get('facture_id')))
                        ->formatStateUsing(function ($get) {
                            $factureId = $get('facture_id');
                            if (!$factureId) return null;
                            $facture = Facture::find($factureId);
                            return $facture ? number_format($facture->montant, 2, ',', ' ') : null;
                        })
                        ->placeholder(function ($get) {
                            $factureId = $get('facture_id');
                            if (!$factureId) return null;
                            $facture = Facture::find($factureId);
                            return $facture ? number_format($facture->montant, 2, ',', ' ') : null;
                        }),

                    TextInput::make('reste_actuel')
                        ->label('Reste à payer actuel')
                        ->prefix('DH')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($get) => filled($get('facture_id')))
                        ->formatStateUsing(function ($get, ?Model $record) {
                            $factureId = $get('facture_id');
                            if (!$factureId) return null;
                            $facture = Facture::with('paiements')->find($factureId);
                            if (!$facture) return null;
                            $dejaPaye = $facture->paiements
                                ->when($record?->id, fn ($p) => $p->where('id', '!=', $record->id))
                                ->sum('montant');
                            return number_format(max(0, $facture->montant - $dejaPaye), 2, ',', ' ');
                        })
                        ->placeholder(function ($get, ?Model $record) {
                            $factureId = $get('facture_id');
                            if (!$factureId) return null;
                            $facture = Facture::with('paiements')->find($factureId);
                            if (!$facture) return null;
                            $dejaPaye = $facture->paiements
                                ->when($record?->id, fn ($p) => $p->where('id', '!=', $record->id))
                                ->sum('montant');
                            return number_format(max(0, $facture->montant - $dejaPaye), 2, ',', ' ');
                        }),

                    TextInput::make('montant')
                        ->label('Montant réglé')
                        ->numeric()
                        ->prefix('DH')
                        ->live(debounce: 300)
                        ->required(),

                    TextInput::make('nouveau_reste')
                        ->label('Reste après ce versement')
                        ->prefix('DH')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($get) => filled($get('facture_id')))
                        ->formatStateUsing(function ($get, ?Model $record) {
                            $factureId = $get('facture_id');
                            if (!$factureId) return null;
                            $facture = Facture::with('paiements')->find($factureId);
                            if (!$facture) return null;
                            $dejaPaye = $facture->paiements
                                ->when($record?->id, fn ($p) => $p->where('id', '!=', $record->id))
                                ->sum('montant');
                            $resteAvant = max(0, $facture->montant - $dejaPaye);
                            $montantSaisi = (float) ($get('montant') ?? 0);
                            $nouveauReste = max(0, $resteAvant - $montantSaisi);
                            return number_format($nouveauReste, 2, ',', ' ');
                        })
                        ->placeholder(function ($get, ?Model $record) {
                            $factureId = $get('facture_id');
                            if (!$factureId) return null;
                            $facture = Facture::with('paiements')->find($factureId);
                            if (!$facture) return null;
                            $dejaPaye = $facture->paiements
                                ->when($record?->id, fn ($p) => $p->where('id', '!=', $record->id))
                                ->sum('montant');
                            $resteAvant = max(0, $facture->montant - $dejaPaye);
                            $montantSaisi = (float) ($get('montant') ?? 0);
                            $nouveauReste = max(0, $resteAvant - $montantSaisi);
                            return number_format($nouveauReste, 2, ',', ' ');
                        }),

                    DatePicker::make('date')
                        ->label('Date de réception')
                        ->default(now())
                        ->required(),

                    Select::make('methode')
                        ->label('Mode de paiement')
                        ->options([
                            'virement' => 'Virement bancaire',
                            'cheque'   => 'Chèque',
                            'especes'  => 'Espèces',
                        ])
                        ->default('virement')
                        ->required(),
                ]),
        ]);
    }
}