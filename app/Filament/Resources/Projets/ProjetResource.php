<?php

namespace App\Filament\Resources\Projets;

use App\Filament\Resources\Projets\Pages\CreateProjet;
use App\Filament\Resources\Projets\Pages\EditProjet;
use App\Filament\Resources\Projets\Pages\ListProjets;
use App\Filament\Resources\Projets\Schemas\ProjetForm;
use App\Filament\Resources\Projets\Tables\ProjetsTable;
use App\Models\Projet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProjetResource extends Resource
{
    protected static ?string $model = Projet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static string|UnitEnum|null $navigationGroup = 'Clients & Commercial';

    protected static ?string $modelLabel = 'Projet Web';

    protected static ?string $pluralModelLabel = 'Projets Web';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ProjetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjets::route('/'),
            'create' => CreateProjet::route('/create'),
            'edit' => EditProjet::route('/{record}/edit'),
        ];
    }
}
