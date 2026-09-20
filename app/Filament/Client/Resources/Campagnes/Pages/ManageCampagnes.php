<?php

namespace App\Filament\Client\Resources\Campagnes\Pages;

use App\Filament\Client\Resources\Campagnes\CampagneResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCampagnes extends ManageRecords
{
    protected static string $resource = CampagneResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
