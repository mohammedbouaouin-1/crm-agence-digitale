<?php

namespace App\Console\Commands;

use App\Models\Facture;
use Illuminate\Console\Command;

class MettreAJourFacturesEnRetard extends Command
{
    protected $signature = 'app:mettre-a-jour-factures-en-retard';

    protected $description = 'Recalcule le statut des factures dont l\'échéance est dépassée';

    public function handle()
    {
        $factures = Facture::whereIn('statut', ['en_attente', 'partiellement_payee'])
            ->where('date_echeance', '<', now())
            ->get();

        foreach ($factures as $facture) {
            $facture->mettreAJourStatut();
        }

        $this->info($factures->count() . ' facture(s) mise(s) à jour.');
    }
}
