<?php

namespace App\Filament\Client\Resources\Demandes;

use App\Filament\Client\Resources\Demandes\Pages\ManageDemandes;
use App\Models\DemandeClient;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class DemandeResource extends Resource
{
    protected static ?string $model = DemandeClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Assistance';

    protected static ?string $modelLabel = 'Demande / Question';

    protected static ?string $pluralModelLabel = 'Mes Demandes';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('client_id', auth()->user()?->client_id);
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sujet')
                    ->label('Sujet de votre demande')
                    ->options([
                        'Question sur une campagne'  => 'Question sur une campagne',
                        'Question sur une facture'   => 'Question sur une facture',
                        'Question sur un projet web' => 'Question sur un projet web',
                        'Autre demande'              => 'Autre demande',
                    ])
                    ->required(),

                Textarea::make('message')
                    ->label('Votre message')
                    ->placeholder('Expliquez en détail votre demande...')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sujet')
            ->columns([
                TextColumn::make('sujet')
                    ->label('Sujet')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60),

                TextColumn::make('traite')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Traitée' : 'En attente'),

                TextColumn::make('created_at')
                    ->label('Envoyée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDemandes::route('/'),
        ];
    }
}
