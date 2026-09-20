<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'devis_id',
        'nom',
        'type_site',
        'budget',
        'date_debut',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'statut',
        'nom_domaine',
        'url_site',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_livraison_prevue' => 'date',
        'date_livraison_reelle' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class);
    }
}
