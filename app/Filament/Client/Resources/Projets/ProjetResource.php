<?php

namespace App\Filament\Client\Resources\Projets;

use App\Filament\Client\Resources\Projets\Pages\ManageProjets;
use App\Models\Projet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ProjetResource extends Resource
{
    protected static ?string $model = Projet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static string|UnitEnum|null $navigationGroup = 'Mes Activités';

    protected static ?string $modelLabel = 'Mon Projet Web';

    protected static ?string $pluralModelLabel = 'Mes Projets Web';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('client_id', auth()->user()?->client_id);
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
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Projet')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('type_site')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'vitrine'         => 'Site vitrine',
                        'e-commerce'      => 'E-commerce',
                        'application_web' => 'Application web',
                        'refonte'         => 'Refonte',
                        default           => $state,
                    }),

                TextColumn::make('statut')
                    ->label('Avancement')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'maquette'      => 'secondary',
                        'developpement' => 'info',
                        'tests'         => 'warning',
                        'livre'         => 'success',
                        'en_pause'      => 'danger',
                        default         => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'maquette'      => 'Maquette',
                        'developpement' => 'Développement',
                        'tests'         => 'Tests',
                        'livre'         => 'Livré',
                        'en_pause'      => 'En pause',
                        default         => $state,
                    }),

                TextColumn::make('date_livraison_prevue')
                    ->label('Livraison prévue')
                    ->date('d/m/Y'),

                TextColumn::make('url_site')
                    ->label('Accès au site')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => $record->url_site, shouldOpenInNewTab: true)
                    ->placeholder('En cours de création'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProjets::route('/'),
        ];
    }
}
