<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campagne extends Model
{
    use HasFactory;
    
    protected $fillable = ['client_id', 'nom', 'compte_cible', 'url_cible', 'type', 'plateforme', 'budget', 'date_debut', 'date_fin', 'statut'];
    protected $casts = ['date_debut' => 'date', 'date_fin' => 'date'];

    public function client() { return $this->belongsTo(Client::class); }
}