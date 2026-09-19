<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\NoteHistoriques\NoteHistoriqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $relatedResource = NoteHistoriqueResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
