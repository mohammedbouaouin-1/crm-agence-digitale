<?php

namespace App\Filament\Client\Widgets;

use App\Models\Facture;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FacturesARegler extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): ?string
    {
        return 'Factures à régler';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Facture::query()
                    ->where('client_id', auth()->user()?->client_id)
                    ->where('statut', '!=', 'payee')
                    ->orderBy('date_echeance', 'asc')
            )
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Facture')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('montant')
                    ->label('Montant total')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ').' DH'),

                TextColumn::make('reste_a_payer')
                    ->label('Reste dû')
                    ->state(fn (Facture $record): string => number_format(max(0, $record->montant - ($record->totalPaye ?? 0)), 2, ',', ' ').' DH')
                    ->weight('bold')
                    ->badge()
                    ->color(function (Facture $record): string {
                        $reste = max(0, $record->montant - ($record->totalPaye ?? 0));

                        return $reste <= 0 ? 'success' : ($record->date_echeance?->isPast() ? 'danger' : 'warning');
                    }),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'partiellement_payee' => 'warning',
                        'en_attente' => 'secondary',
                        'en_retard' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'partiellement_payee' => 'Partiellement payée',
                        'en_attente' => 'En attente',
                        'en_retard' => 'En retard',
                        default => $state,
                    }),

                TextColumn::make('date_echeance')
                    ->label('Échéance')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record->date_echeance?->isPast() ? 'danger' : null),
            ])
            ->recordActions([
                Action::make('telecharger_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Facture $record) => route('factures.pdf', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
