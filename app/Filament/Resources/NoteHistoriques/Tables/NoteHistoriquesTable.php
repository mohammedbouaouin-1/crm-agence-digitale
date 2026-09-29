<?php

namespace App\Filament\Resources\NoteHistoriques\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NoteHistoriquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Type d\'échange')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'reunion' => 'heroicon-o-user-group',
                        'appel' => 'heroicon-o-phone',
                        'email' => 'heroicon-o-envelope',
                        default => 'heroicon-o-chat-bubble-bottom-center-text',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'reunion' => 'success',
                        'appel' => 'info',
                        'email' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'reunion' => 'Réunion',
                        'appel' => 'Appel',
                        'email' => 'Email',
                        'autre' => 'Autre',
                        default => $state,
                    }),

                TextColumn::make('contenu')
                    ->label('Note')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->contenu),

                TextColumn::make('prochaine_action')
                    ->label('Prochaine action')
                    ->date('d/m/Y')
                    ->icon('heroicon-o-calendar')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Daté du')
                    ->dateTime('d/m/Y H:i')
                    ->icon('heroicon-o-clock')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type d\'échange')
                    ->options([
                        'appel' => 'Appel',
                        'email' => 'Email',
                        'reunion' => 'Réunion',
                        'autre' => 'Autre',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
