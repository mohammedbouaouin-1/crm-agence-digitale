<?php

namespace App\Filament\Resources\Devis;

use App\Filament\Resources\Devis\Pages\CreateDevis;
use App\Filament\Resources\Devis\Pages\EditDevis;
use App\Filament\Resources\Devis\Pages\ListDevis;
use App\Filament\Resources\Devis\Schemas\DevisForm;
use App\Filament\Resources\Devis\Tables\DevisTable;
use App\Models\Devis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DevisResource extends Resource
{
    protected static ?string $model = Devis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $recordTitleAttribute = 'numero';

    protected static ?string $modelLabel = 'Devis';

    protected static ?string $pluralModelLabel = 'Devis';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('statut', ['envoye', 'brouillon'])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return DevisForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevisTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['client', 'projets', 'campagnes', 'factures']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDevis::route('/'),
            'create' => CreateDevis::route('/create'),
            'edit' => EditDevis::route('/{record}/edit'),
        ];
    }
}
