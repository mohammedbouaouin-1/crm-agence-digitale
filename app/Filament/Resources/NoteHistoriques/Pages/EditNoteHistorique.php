<?php

namespace App\Filament\Resources\NoteHistoriques\Pages;

use App\Filament\Resources\NoteHistoriques\NoteHistoriqueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNoteHistorique extends EditRecord
{
    protected static string $resource = NoteHistoriqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
