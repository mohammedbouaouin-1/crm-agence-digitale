<?php

namespace App\Filament\Client\Resources\Devis;

use App\Filament\Client\Resources\Devis\Pages\ManageDevis;
use App\Mail\DevisAccepteNotification;
use App\Models\Devis;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use UnitEnum;

class DevisResource extends Resource
{
    protected static ?string $model = Devis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Mes Documents';

    protected static ?string $recordTitleAttribute = 'numero';

    protected static ?string $modelLabel = 'Devis';

    protected static ?string $pluralModelLabel = 'Mes Devis';

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
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero')
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Devis')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('titre')
                    ->label('Objet de la proposition')
                    ->searchable(),

                TextColumn::make('montant')
                    ->label('Montant Net')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ').' DH')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('date_emission')
                    ->label('Date d\'émission')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_validite')
                    ->label('Valable jusqu\'au')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->date_validite?->isPast() && $record->statut !== 'accepte' ? 'danger' : null),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepte' => 'success',
                        'envoye' => 'info',
                        'brouillon' => 'secondary',
                        'refuse' => 'danger',
                        'expire' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accepte' => 'Accepté',
                        'envoye' => 'En attente de votre accord',
                        'brouillon' => 'Brouillon',
                        'refuse' => 'Refusé',
                        'expire' => 'Expiré',
                        default => ucfirst($state),
                    })
                    ->description(fn (Devis $record) => $record->accepte_le ? 'Validé le '.$record->accepte_le->format('d/m/Y H:i') : null),
            ])
            ->filters([])
            ->recordActions([
                Action::make('telecharger_pdf')
                    ->label('Télécharger PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Devis $record) => route('devis.pdf', $record))
                    ->openUrlInNewTab(),

                Action::make('accepter_devis')
                    ->label('Accepter le devis')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Devis $record) => $record->statut === 'envoye')
                    ->requiresConfirmation()
                    ->modalHeading('Accepter cette proposition commerciale ?')
                    ->modalDescription('En validant ce devis, vous confirmez votre accord pour la réalisation des prestations indiquées.')
                    ->modalSubmitActionLabel('Oui, j\'accepte le devis')
                    ->action(function (Devis $record) {
                        $record->update([
                            'statut' => 'accepte',
                            'accepte_le' => now(),
                            'ip_acceptation' => request()->ip() ?? '127.0.0.1',
                        ]);

                        try {
                            Mail::to('webmarko.company@gmail.com')->send(
                                new DevisAccepteNotification($record->fresh())
                            );
                        } catch (\Throwable) {
                        }

                        Notification::make()
                            ->title('Devis accepté')
                            ->body('Votre accord certifié a été transmis à l\'agence avec succès.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDevis::route('/'),
        ];
    }
}
