<?php

namespace App\Filament\Resources\NoteHistoriques\Pages;

use App\Filament\Resources\NoteHistoriques\NoteHistoriqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNoteHistoriques extends ListRecords
{
    protected static string $resource = NoteHistoriqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nouvelle note'),
        ];
    }
}
