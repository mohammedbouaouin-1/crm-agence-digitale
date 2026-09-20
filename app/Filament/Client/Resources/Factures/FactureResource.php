<?php

namespace App\Filament\Client\Resources\Factures;

use App\Filament\Client\Resources\Factures\Pages\ManageFactures;
use App\Models\Facture;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class FactureResource extends Resource
{
    protected static ?string $model = Facture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Mes Documents';

    protected static ?string $recordTitleAttribute = 'numero';

    protected static ?string $modelLabel = 'Facture';

    protected static ?string $pluralModelLabel = 'Mes Factures';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('client_id', auth()->user()->client_id);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')->label('N° Facture')->disabled(),
                TextInput::make('montant')->label('Montant (DH)')->numeric()->disabled(),
                DatePicker::make('date_emission')->label('Date d\'émission')->disabled(),
                DatePicker::make('date_echeance')->label('Date d\'échéance')->disabled(),
                Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'partiellement_payee' => 'Partiellement payée',
                        'payee' => 'Payée',
                        'en_retard' => 'En retard',
                    ])
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero')
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Facture')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('montant')
                    ->label('Montant TTC')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ').' DH')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('reste_a_payer')
                    ->label('Reste à payer')
                    ->state(function (Facture $record): string {
                        $reste = max(0, $record->montant - ($record->totalPaye ?? 0));

                        return number_format($reste, 2, ',', ' ').' DH';
                    })
                    ->badge()
                    ->color(function (Facture $record): string {
                        $reste = max(0, $record->montant - ($record->totalPaye ?? 0));

                        return $reste <= 0 ? 'success' : 'warning';
                    }),

                TextColumn::make('date_emission')
                    ->label('Date d\'émission')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_echeance')
                    ->label('Date d\'échéance')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'payee' => 'success',
                        'partiellement_payee' => 'warning',
                        'en_attente' => 'secondary',
                        'en_retard' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'payee' => 'Payée',
                        'partiellement_payee' => 'Partiellement payée',
                        'en_attente' => 'En attente',
                        'en_retard' => 'En retard',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([])
            ->recordActions([
                Action::make('telecharger_pdf')
                    ->label('Télécharger PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Facture $record) => route('factures.pdf', $record))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFactures::route('/'),
        ];
    }
}
