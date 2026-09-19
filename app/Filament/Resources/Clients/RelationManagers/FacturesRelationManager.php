<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\Factures\FactureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class FacturesRelationManager extends RelationManager
{
    protected static string $relationship = 'factures';

    protected static ?string $relatedResource = FactureResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
