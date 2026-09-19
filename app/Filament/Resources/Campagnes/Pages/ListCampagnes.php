<?php

namespace App\Filament\Resources\Campagnes\Pages;

use App\Filament\Resources\Campagnes\CampagneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampagnes extends ListRecords
{
    protected static string $resource = CampagneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nouvelle campagne'),
        ];
    }
}
