<?php

namespace App\Mail;

use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouvelleFactureDisponible extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Facture $facture) {}

    public function build()
    {
        $this->facture->load(['client', 'devis', 'paiements']);

        return $this->subject('Webmarko — Facture officielle N° '.$this->facture->numero)
            ->view('emails.nouvelle-facture')
            ->text('emails.text.nouvelle-facture')
            ->with(['facture' => $this->facture])
            ->attachData(
                Pdf::loadView('pdf.facture', ['facture' => $this->facture])->output(),
                "facture-{$this->facture->numero}.pdf"
            );
    }
}
