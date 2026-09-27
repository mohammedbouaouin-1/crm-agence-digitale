<?php

namespace App\Mail;

use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RelanceFacture extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Facture $facture) {}

    public function build()
    {
        $this->facture->load(['client', 'paiements', 'devis']);

        return $this->subject('Webmarko — Suivi d\'échéance de la facture N° '.$this->facture->numero)
            ->view('emails.relance-facture')
            ->text('emails.text.relance-facture')
            ->with(['facture' => $this->facture])
            ->attachData(
                Pdf::loadView('pdf.facture', ['facture' => $this->facture])->output(),
                "facture-{$this->facture->numero}.pdf"
            );
    }
}
