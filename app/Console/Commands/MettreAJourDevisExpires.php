<?php

namespace App\Console\Commands;

use App\Models\Devis;
use Illuminate\Console\Command;

class MettreAJourDevisExpires extends Command
{
    protected $signature = 'app:mettre-a-jour-devis-expires';

    protected $description = 'Marque comme expirés les devis dont la date de validité est dépassée';

    public function handle()
    {
        $count = Devis::where('date_validite', '<', now()->startOfDay())
            ->whereIn('statut', ['brouillon', 'envoye'])
            ->update(['statut' => 'expire']);

        $this->info($count.' devis marqué(s) comme expiré(s).');
    }
}
