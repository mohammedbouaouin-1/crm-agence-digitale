<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageClient extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Client $client,
        public string $sujet,
        public string $messageContent,
    ) {}

    public function build()
    {
        return $this->subject('Webmarko [Support Client] — Nouveau message de '.$this->client->nom)
            ->view('emails.message-client')
            ->text('emails.text.message-client')
            ->with([
                'client' => $this->client,
                'sujet' => $this->sujet,
                'messageContent' => $this->messageContent,
            ])
            ->replyTo($this->client->email, $this->client->nom);
    }
}
