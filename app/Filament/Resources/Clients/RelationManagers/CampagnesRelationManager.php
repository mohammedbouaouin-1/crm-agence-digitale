<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\Campagnes\CampagneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CampagnesRelationManager extends RelationManager
{
    protected static string $relationship = 'campagnes';

    protected static ?string $relatedResource = CampagneResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
