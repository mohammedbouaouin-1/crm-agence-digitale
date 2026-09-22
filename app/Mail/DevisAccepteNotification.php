<?php

namespace App\Mail;

use App\Models\Devis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DevisAccepteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Devis $devis) {}

    public function build()
    {
        $this->devis->load('client');

        $clientNom = $this->devis->client?->nom ?? 'Client';

        return $this->subject("[Accord Client] Devis {$this->devis->numero} accepté — {$clientNom}")
            ->view('emails.devis-accepte')
            ->with(['devis' => $this->devis])
            ->attachData(
                Pdf::loadView('pdf.devis', ['devis' => $this->devis])->output(),
                "devis-{$this->devis->numero}-signe.pdf"
            );
    }
}
