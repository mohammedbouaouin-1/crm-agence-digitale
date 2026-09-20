<?php

namespace App\Filament\Resources\DemandeClients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DemandeClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Détails de la demande client')
                ->description('Consulter et traiter la sollicitation client')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->columns(2)
                ->schema([
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'nom')
                        ->disabled(),

                    TextInput::make('sujet')
                        ->label('Sujet')
                        ->disabled(),

                    Textarea::make('message')
                        ->label('Message du client')
                        ->disabled()
                        ->columnSpanFull()
                        ->rows(5),

                    Toggle::make('traite')
                        ->label('Marquer comme traitée')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
