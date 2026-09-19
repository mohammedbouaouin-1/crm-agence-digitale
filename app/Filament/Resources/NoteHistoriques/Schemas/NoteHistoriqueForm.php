<?php

namespace App\Filament\Resources\NoteHistoriques\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NoteHistoriqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Détails de l\'échange')
                ->description('Consigner une note ou un compte-rendu d\'échange client')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->columns(2)
                ->schema([
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'nom')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('type')
                        ->label('Type d\'échange')
                        ->options([
                            'appel'   => 'Appel',
                            'email'   => 'Email',
                            'reunion' => 'Réunion',
                            'autre'   => 'Autre',
                        ])
                        ->required(),

                    Textarea::make('contenu')
                        ->label('Contenu de la note')
                        ->rows(4)
                        ->required()
                        ->columnSpanFull(),

                    DatePicker::make('prochaine_action')
                        ->label('Date de la prochaine action')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}