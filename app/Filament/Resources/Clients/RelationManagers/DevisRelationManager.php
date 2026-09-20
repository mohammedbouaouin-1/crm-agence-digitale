<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\Devis\DevisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DevisRelationManager extends RelationManager
{
    protected static string $relationship = 'devis';

    protected static ?string $relatedResource = DevisResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
