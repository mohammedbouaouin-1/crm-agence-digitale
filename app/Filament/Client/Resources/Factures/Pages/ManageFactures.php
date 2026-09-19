<?php

namespace App\Filament\Client\Resources\Factures\Pages;

use App\Filament\Client\Resources\Factures\FactureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFactures extends ManageRecords
{
    protected static string $resource = FactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
          
        ];
    }
}
