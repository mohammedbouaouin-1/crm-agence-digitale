<?php

namespace App\Filament\Client\Resources\Demandes\Pages;

use App\Filament\Client\Resources\Demandes\DemandeResource;
use App\Mail\MessageClient;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Mail;

class ManageDemandes extends ManageRecords
{
    protected static string $resource = DemandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nouvelle demande')
                ->modalHeading('Envoyer une nouvelle demande')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['client_id'] = auth()->user()?->client_id;

                    return $data;
                })
                ->after(function ($record) {
                    $client = auth()->user()?->client;
                    if ($client) {
                        try {
                            Mail::to('webmarko.company@gmail.com')->send(
                                new MessageClient(
                                    $client,
                                    $record->sujet,
                                    $record->message,
                                )
                            );
                        } catch (\Exception $e) {
                            // Ignorer les erreurs d'envoi en local sans serveur SMTP
                        }
                    }

                    Notification::make()
                        ->title('Message envoyé')
                        ->body('Notre équipe vous répondra rapidement.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
