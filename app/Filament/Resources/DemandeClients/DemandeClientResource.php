<?php

namespace App\Filament\Resources\DemandeClients;

use App\Filament\Resources\DemandeClients\Pages\ManageDemandeClients;
use App\Filament\Resources\DemandeClients\Schemas\DemandeClientForm;
use App\Filament\Resources\DemandeClients\Tables\DemandeClientsTable;
use App\Models\DemandeClient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DemandeClientResource extends Resource
{
    protected static ?string $model = DemandeClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Clients & Commercial';

    protected static ?string $modelLabel = 'Demande Client';

    protected static ?string $pluralModelLabel = 'Demandes Clients';

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return DemandeClientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DemandeClientsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDemandeClients::route('/'),
        ];
    }
}
