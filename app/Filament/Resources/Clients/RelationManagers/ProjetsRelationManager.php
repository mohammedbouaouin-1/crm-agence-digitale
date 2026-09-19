<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\Projets\ProjetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProjetsRelationManager extends RelationManager
{
    protected static string $relationship = 'projets';

    protected static ?string $relatedResource = ProjetResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
