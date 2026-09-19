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
        $this->facture->load(['client', 'paiements']);

        return $this->subject('Relance — Facture ' . $this->facture->numero . ' en attente de règlement')
            ->view('emails.relance-facture')
            ->with(['facture' => $this->facture])
            ->attachData(
                Pdf::loadView('pdf.facture', ['facture' => $this->facture])->output(),
                "facture-{$this->facture->numero}.pdf"
            );
    }
}
