<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ParametresAgence extends Page
{
    protected static ?string $title = 'Paramètres de l\'agence';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Configuration';

    protected static ?string $navigationLabel = 'Paramètres';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = Setting::getAll();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Identité de l\'agence')
                        ->description('Coordonnées générales et informations de contact')
                        ->icon('heroicon-o-building-office-2')
                        ->columns(2)
                        ->schema([
                            TextInput::make('agence_nom')
                                ->label('Nom de l\'agence')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('agence_slogan')
                                ->label('Slogan / Activité')
                                ->maxLength(255),

                            TextInput::make('agence_email')
                                ->label('Email officiel')
                                ->email()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('agence_telephone')
                                ->label('Téléphone')
                                ->tel()
                                ->maxLength(50),

                            TextInput::make('agence_site_web')
                                ->label('Site web')
                                ->url()
                                ->maxLength(255),

                            TextInput::make('agence_adresse')
                                ->label('Adresse du siège social')
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ]),

                    Section::make('Coordonnées bancaires')
                        ->description('Coordonnées affichées sur les factures pour les virements des clients')
                        ->icon('heroicon-o-credit-card')
                        ->columns(2)
                        ->schema([
                            TextInput::make('banque_nom')
                                ->label('Nom de la banque')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('banque_titulaire')
                                ->label('Titulaire du compte')
                                ->required()
                                ->maxLength(150),

                            TextInput::make('banque_rib')
                                ->label('RIB (Relevé d\'Identité Bancaire — 24 chiffres)')
                                ->helperText('Numéro de compte à 24 chiffres affiché sur les factures clients.')
                                ->required()
                                ->maxLength(40)
                                ->columnSpanFull(),
                        ]),

                    Section::make('Facturation & Devis')
                        ->description('Règles par défaut et mentions légales de bas de page')
                        ->icon('heroicon-o-document-text')
                        ->columns(2)
                        ->schema([
                            TextInput::make('devis_validite_jours')
                                ->label('Durée de validité des devis (jours)')
                                ->numeric()
                                ->suffix('jours'),

                            TextInput::make('facture_echeance_jours')
                                ->label('Délai de paiement des factures (jours)')
                                ->numeric()
                                ->suffix('jours'),

                            TextInput::make('email_notifications')
                                ->label('Email de notification des alertes')
                                ->email()
                                ->helperText('Adresse recevant les alertes de nouveaux devis acceptés et nouvelles demandes.')
                                ->columnSpanFull(),

                            Textarea::make('facture_mentions')
                                ->label('Mentions légales de pied de page')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                ])
                    ->statePath('data')
                    ->livewireSubmitHandler('enregistrer')
                    ->footer([
                        Actions::make([
                            Action::make('enregistrer')
                                ->label('Enregistrer les modifications')
                                ->icon('heroicon-o-check')
                                ->color('primary')
                                ->submit('enregistrer'),
                        ]),
                    ]),
            ]);
    }

    public function enregistrer(): void
    {
        Setting::setMany($this->data ?? []);

        Notification::make()
            ->title('Paramètres enregistrés')
            ->body('Les coordonnées et préférences de l\'agence ont été mises à jour avec succès.')
            ->success()
            ->send();
    }
}
