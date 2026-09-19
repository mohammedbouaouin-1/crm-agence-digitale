<?php

namespace App\Filament\Widgets;

use App\Models\Facture;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DernieresActivites extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): ?string
    {
        return 'Factures à surveiller';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Facture::query()
                    ->where('statut', '!=', 'payee')
                    ->orderBy('date_echeance', 'asc')
                    ->limit(10)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Facture')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable(),

                TextColumn::make('montant')
                    ->label('Montant')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ') . ' DH')
                    ->weight('bold'),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'partiellement_payee' => 'warning',
                        'en_attente'          => 'secondary',
                        'en_retard'           => 'danger',
                        default               => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'partiellement_payee' => 'Partiellement payée',
                        'en_attente'          => 'En attente',
                        'en_retard'           => 'En retard',
                        default               => $state,
                    }),

                TextColumn::make('date_emission')
                    ->label('Émission')
                    ->date('d/m/Y'),

                TextColumn::make('date_echeance')
                    ->label('Échéance')
                    ->date('d/m/Y')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->statut === 'en_retard' => 'danger',
                        $record->date_echeance?->diffInDays(now()) <= 7 => 'warning',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('voir_tout')
                    ->label('Voir toutes les factures')
                    ->url(\App\Filament\Resources\Factures\FactureResource::getUrl('index')),
            ]);
    }
}