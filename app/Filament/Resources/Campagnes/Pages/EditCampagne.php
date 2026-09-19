<?php

namespace App\Filament\Resources\Campagnes\Pages;

use App\Filament\Resources\Campagnes\CampagneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCampagne extends EditRecord
{
    protected static string $resource = CampagneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
