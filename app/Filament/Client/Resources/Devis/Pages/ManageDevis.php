<?php

namespace App\Filament\Client\Resources\Devis\Pages;

use App\Filament\Client\Resources\Devis\DevisResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDevis extends ManageRecords
{
    protected static string $resource = DevisResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
