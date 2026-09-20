<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'entreprise', 'email', 'telephone', 'adresse', 'secteur_activite', 'statut'];

    public function campagnes() { return $this->hasMany(Campagne::class); }
    public function factures() { return $this->hasMany(Facture::class); }
    public function notes() { return $this->hasMany(NoteHistorique::class); }
    public function projets() { return $this->hasMany(Projet::class); }
    public function demandes() { return $this->hasMany(DemandeClient::class); }
    public function devis() { return $this->hasMany(Devis::class); }
}