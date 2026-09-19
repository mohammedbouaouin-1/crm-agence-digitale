<?php

namespace App\Filament\Client\Resources\Campagnes;

use App\Filament\Client\Resources\Campagnes\Pages\ManageCampagnes;
use App\Models\Campagne;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CampagneResource extends Resource
{
    protected static ?string $model = Campagne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Mes Activités';

    protected static ?string $recordTitleAttribute = 'nom';

    protected static ?string $modelLabel = 'Campagne';

    protected static ?string $pluralModelLabel = 'Mes Campagnes';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('client_id', auth()->user()->client_id);
    }

    public static function canCreate(): bool
    {
        return false;
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
                TextInput::make('nom')->label('Campagne')->disabled(),
                TextInput::make('compte_cible')->label('Compte / Page')->disabled(),
                TextInput::make('url_cible')->label('Lien / URL')->disabled(),
                TextInput::make('type')->label('Type')->disabled(),
                TextInput::make('plateforme')->label('Plateforme')->disabled(),
                TextInput::make('budget')->label('Budget (DH)')->numeric()->disabled(),
                DatePicker::make('date_debut')->label('Date de début')->disabled(),
                DatePicker::make('date_fin')->label('Date de fin')->disabled(),
                Select::make('statut')
                    ->label('Statut')
                    ->options(['en_cours' => 'En cours', 'terminee' => 'Terminée', 'en_pause' => 'En pause'])
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom de la campagne')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('compte_cible')
                    ->label('Compte / Page')
                    ->searchable()
                    ->placeholder('—')
                    ->url(fn ($record) => $record->url_cible ?: null, shouldOpenInNewTab: true)
                    ->icon(fn ($record) => $record->url_cible ? 'heroicon-o-arrow-top-right-on-square' : null),

                TextColumn::make('type')
                    ->label('Type'),

                TextColumn::make('plateforme')
                    ->label('Plateforme')
                    ->placeholder('—'),

                TextColumn::make('budget')
                    ->label('Budget')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2, ',', ' ') . ' DH' : '—')
                    ->sortable(),

                TextColumn::make('date_debut')
                    ->label('Date début')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_fin')
                    ->label('Date fin')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('En cours'),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en_cours' => 'success',
                        'terminee' => 'secondary',
                        'en_pause' => 'warning',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'en_cours' => 'En cours',
                        'terminee' => 'Terminée',
                        'en_pause' => 'En pause',
                        default    => ucfirst($state),
                    }),
            ])
            ->filters([])
            ->recordActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCampagnes::route('/'),
        ];
    }
}