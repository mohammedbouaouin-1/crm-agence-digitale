<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccesClientCree extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nomClient,
        public string $email,
        public string $motDePasse,
    ) {}

    public function build()
    {
        return $this->subject('Votre accès au portail client')
            ->view('emails.acces-client')
            ->with([
                'nomClient' => $this->nomClient,
                'email' => $this->email,
                'motDePasse' => $this->motDePasse,
                'lien' => url('/client'),
            ]);
    }
}
