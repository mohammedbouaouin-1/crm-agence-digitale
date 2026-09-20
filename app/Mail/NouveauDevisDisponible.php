<?php

namespace App\Mail;

use App\Models\Devis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouveauDevisDisponible extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Devis $devis) {}

    public function build()
    {
        $this->devis->load('client');

        return $this->subject('Nouvelle proposition commerciale — Devis '.$this->devis->numero)
            ->view('emails.nouveau-devis')
            ->with(['devis' => $this->devis])
            ->attachData(
                Pdf::loadView('pdf.devis', ['devis' => $this->devis])->output(),
                "devis-{$this->devis->numero}.pdf"
            );
    }
}
