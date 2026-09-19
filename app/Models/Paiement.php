<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    
    protected $fillable = ['facture_id', 'montant', 'date', 'methode'];
    protected $casts = ['date' => 'date'];

    protected static function booted()
    {
        static::saved(fn (Paiement $p) => $p->facture?->mettreAJourStatut());
        static::deleted(fn (Paiement $p) => $p->facture?->mettreAJourStatut());
    }

    public function facture() { return $this->belongsTo(Facture::class); }
}