<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campagne extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'devis_id',
        'nom',
        'compte_cible',
        'url_cible',
        'type',
        'plateforme',
        'budget',
        'date_debut',
        'date_fin',
        'statut',
    ];
    
    protected $casts = ['date_debut' => 'date', 'date_fin' => 'date'];

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function devis(): BelongsTo { return $this->belongsTo(Devis::class); }
}