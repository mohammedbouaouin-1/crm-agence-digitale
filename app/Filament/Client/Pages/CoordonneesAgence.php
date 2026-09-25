<?php

namespace App\Filament\Client\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use UnitEnum;

class CoordonneesAgence extends Page
{
    protected static ?string $title = 'Coordonnées & RIB de l\'agence';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Informations';

    protected static ?string $navigationLabel = 'Coordonnées & RIB';

    protected static ?int $navigationSort = 10;

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coordonnées Bancaires pour vos Règlements')
                    ->description('Informations officielles à reporter lors de vos virements bancaires')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('banque')
                            ->label('Établissement bancaire')
                            ->content(fn () => Setting::get('banque_nom', 'Attijariwafa Bank')),

                        Placeholder::make('titulaire')
                            ->label('Titulaire du compte')
                            ->content(fn () => Setting::get('banque_titulaire', 'Webmarko SARL')),

                        Placeholder::make('rib')
                            ->label('RIB (Relevé d\'Identité Bancaire — 24 chiffres)')
                            ->content(fn () => new HtmlString('<div style="padding: 10px 14px; background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.25); border-radius: 8px; font-family: monospace; font-size: 15px; font-weight: bold; letter-spacing: 1px; color: #4f46e5; user-select: all;">'.e(Setting::get('banque_rib', '007 780 0001234567890123 45')).'</div>'))
                            ->columnSpanFull(),

                        Placeholder::make('consigne')
                            ->label('Consigne de paiement')
                            ->content('Lors de votre virement, merci de mentionner votre numéro de client ou le numéro de facture en référence pour un rapprochement comptable immédiat.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Contact Direct & Assistance')
                    ->description('Notre équipe est à votre disposition pour vous accompagner')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('email')
                            ->label('Email du support')
                            ->content(fn () => Setting::get('agence_email', 'webmarko.company@gmail.com')),

                        Placeholder::make('telephone')
                            ->label('Téléphone assistance')
                            ->content(fn () => Setting::get('agence_telephone', '06 61 51 11 83')),

                        Placeholder::make('horaires')
                            ->label('Disponibilité')
                            ->content('Du Lundi au Vendredi à partir de 08h00'),

                        Placeholder::make('demande_action')
                            ->label('Centre de support')
                            ->content(new HtmlString('<a href="'.url('/client/demandes').'" style="color: #6366f1; font-weight: 600; text-decoration: underline;">Ouvrir un ticket dans le Centre de Demandes &rarr;</a>')),
                    ]),

                Section::make('Adresse de l\'agence')
                    ->description('Notre bureau situé à Fès')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('agence')
                            ->label('Agence')
                            ->content(fn () => Setting::get('agence_nom', 'Webmarko').' — '.Setting::get('agence_slogan', 'Concepteur de sites web')),

                        Placeholder::make('site_web')
                            ->label('Site web officiel')
                            ->content(fn () => Setting::get('agence_site_web', 'https://webmarko.com')),

                        Placeholder::make('adresse')
                            ->label('Adresse de notre bureau')
                            ->content(fn () => Setting::get('agence_adresse', 'Avenue Bir Anzarane Résidence Nour 1er Etage Bureau N 9 Centre Ville, 30000 Fès'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
