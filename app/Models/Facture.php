<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'numero', 'montant', 'date_emission', 'date_echeance', 'statut'];
    protected $casts = ['date_emission' => 'date', 'date_echeance' => 'date'];

    protected static function booted()
    {
        static::saving(function (Facture $facture) {
            $totalPaye = $facture->totalPaye;

            if ($totalPaye >= $facture->montant && $facture->montant > 0) {
                $facture->statut = 'payee';
            } elseif ($facture->date_echeance && now()->startOfDay()->isAfter($facture->date_echeance->endOfDay())) {
                $facture->statut = 'en_retard';
            } elseif ($totalPaye > 0) {
                $facture->statut = 'partiellement_payee';
            } else {
                $facture->statut = 'en_attente';
            }
        });
    }

    public function client() { return $this->belongsTo(Client::class); }
    public function paiements() { return $this->hasMany(Paiement::class); }

    public function getTotalPayeAttribute(): float
    {
        return (float) $this->paiements()->sum('montant');
    }

    public function mettreAJourStatut(): void
    {
        $totalPaye = $this->totalPaye;

        if ($totalPaye >= $this->montant && $this->montant > 0) {
            $this->statut = 'payee';
        } elseif ($this->date_echeance && now()->startOfDay()->isAfter($this->date_echeance->endOfDay())) {
            $this->statut = 'en_retard';
        } elseif ($totalPaye > 0) {
            $this->statut = 'partiellement_payee';
        } else {
            $this->statut = 'en_attente';
        }

        $this->saveQuietly();
    }
}