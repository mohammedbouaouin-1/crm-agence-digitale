<?php

namespace App\Filament\Resources\Campagnes;

use App\Filament\Resources\Campagnes\Pages\CreateCampagne;
use App\Filament\Resources\Campagnes\Pages\EditCampagne;
use App\Filament\Resources\Campagnes\Pages\ListCampagnes;
use App\Filament\Resources\Campagnes\Schemas\CampagneForm;
use App\Filament\Resources\Campagnes\Tables\CampagnesTable;
use App\Models\Campagne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CampagneResource extends Resource
{
    protected static ?string $model = Campagne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Clients & Commercial';

    protected static ?string $recordTitleAttribute = 'nom';

    protected static ?string $modelLabel = 'Campagne';

    protected static ?string $pluralModelLabel = 'Campagnes';

    public static function form(Schema $schema): Schema
    {
        return CampagneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampagnesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampagnes::route('/'),
            'create' => CreateCampagne::route('/create'),
            'edit' => EditCampagne::route('/{record}/edit'),
        ];
    }
}
