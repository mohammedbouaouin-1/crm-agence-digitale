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
                            ->content(function () {
                                $rib = Setting::get('banque_rib', '007 780 0001234567890123 45');
                                $rawRib = preg_replace('/[^0-9]/', '', $rib);

                                return new HtmlString('
                                    <div x-data="{ copied: false }" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 18px; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.25); border-radius: 8px;">
                                        <div style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 16px; font-weight: 700; letter-spacing: 1.5px; color: #4338ca; user-select: all;">
                                            '.e($rib).'
                                        </div>
                                        <button type="button"
                                                @click="navigator.clipboard.writeText(\''.e($rawRib).'\'); copied = true; setTimeout(() => copied = false, 2500)"
                                                style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #4f46e5; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.2s;"
                                                onmouseover="this.style.backgroundColor=\'#4338ca\'"
                                                onmouseout="this.style.backgroundColor=\'#4f46e5\'">
                                            <span x-show="!copied">Copier le RIB</span>
                                            <span x-show="copied" style="display: none; color: #bbf7d0; font-weight: 700;">RIB copié !</span>
                                        </button>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),

                        Placeholder::make('consigne')
                            ->label('Consigne de paiement')
                            ->content(new HtmlString('
                                <div style="padding: 12px 16px; background: rgba(16, 185, 129, 0.06); border-left: 4px solid #10b981; border-radius: 6px; font-size: 13px; color: #065f46; line-height: 1.5;">
                                    <strong>Consigne de virement :</strong> Lors de votre virement, merci de mentionner votre numéro de client ou le numéro de facture en référence pour un rapprochement comptable immédiat.
                                </div>
                            '))
                            ->columnSpanFull(),
                    ]),

                Section::make('Contact Direct & Assistance')
                    ->description('Notre équipe est à votre disposition pour vous accompagner')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('email')
                            ->label('Email du support')
                            ->content(function () {
                                $email = Setting::get('agence_email', 'webmarko.company@gmail.com');

                                return new HtmlString('
                                    <a href="mailto:'.e($email).'" style="color: #4f46e5; font-weight: 600; text-decoration: underline;">
                                        '.e($email).'
                                    </a>
                                ');
                            }),

                        Placeholder::make('telephone')
                            ->label('Téléphone assistance')
                            ->content(function () {
                                $tel = Setting::get('agence_telephone', '06 61 51 11 83');
                                $cleanTel = preg_replace('/[^0-9+]/', '', $tel);

                                return new HtmlString('
                                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                                        <a href="tel:'.e($cleanTel).'" style="color: #0f172a; font-weight: 600; text-decoration: none;">
                                            '.e($tel).'
                                        </a>
                                        <a href="https://wa.me/212661511183" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #15803d; background: #dcfce7; border: 1px solid #86efac; border-radius: 9999px; text-decoration: none;">
                                            Discuter sur WhatsApp
                                        </a>
                                    </div>
                                ');
                            }),

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
                            ->content(function () {
                                $site = Setting::get('agence_site_web', 'https://webmarko.com');

                                return new HtmlString('
                                    <a href="'.e($site).'" target="_blank" rel="noopener noreferrer" style="color: #4f46e5; font-weight: 600; text-decoration: underline;">
                                        '.e($site).' &nearr;
                                    </a>
                                ');
                            }),

                        Placeholder::make('adresse')
                            ->label('Adresse de notre bureau')
                            ->content(function () {
                                $adresse = Setting::get('agence_adresse', 'Avenue Bir Anzarane Résidence Nour 1er Etage Bureau N 9 Centre Ville, 30000 Fès');

                                return new HtmlString('
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <div style="color: #334155; font-size: 14px; line-height: 1.5;">
                                            '.e($adresse).'
                                        </div>
                                        <div>
                                            <a href="https://www.google.com/maps/search/?api=1&query='.urlencode($adresse).'" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: #0284c7; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 6px; text-decoration: none;">
                                                Ouvrir l\'itinéraire sur Google Maps &nearr;
                                            </a>
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
