<?php

namespace App\Filament\Client\Resources\Projets\Pages;

use App\Filament\Client\Resources\Projets\ProjetResource;
use Filament\Resources\Pages\ManageRecords;

class ManageProjets extends ManageRecords
{
    protected static string $resource = ProjetResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
