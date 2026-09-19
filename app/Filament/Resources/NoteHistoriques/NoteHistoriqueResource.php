<?php

namespace App\Filament\Resources\NoteHistoriques;

use App\Filament\Resources\NoteHistoriques\Pages\CreateNoteHistorique;
use App\Filament\Resources\NoteHistoriques\Pages\EditNoteHistorique;
use App\Filament\Resources\NoteHistoriques\Pages\ListNoteHistoriques;
use App\Filament\Resources\NoteHistoriques\Schemas\NoteHistoriqueForm;
use App\Filament\Resources\NoteHistoriques\Tables\NoteHistoriquesTable;
use App\Models\NoteHistorique;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NoteHistoriqueResource extends Resource
{
    protected static ?string $model = NoteHistorique::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Clients & Commercial';

    protected static ?string $modelLabel = 'Note d\'historique';

    protected static ?string $pluralModelLabel = 'Notes d\'historique';

    public static function form(Schema $schema): Schema
    {
        return NoteHistoriqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NoteHistoriquesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNoteHistoriques::route('/'),
            'create' => CreateNoteHistorique::route('/create'),
            'edit' => EditNoteHistorique::route('/{record}/edit'),
        ];
    }
}