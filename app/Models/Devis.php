<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'client_id',
        'numero',
        'titre',
        'montant',
        'date_emission',
        'date_validite',
        'statut',
        'description',
        'conditions',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_validite' => 'date',
        'montant'       => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function (Devis $devis) {
            // Si le devis n'est pas déjà accepté ou refusé, et que la date de validité est passée, on le marque expiré
            if (in_array($devis->statut, ['brouillon', 'envoye']) && $devis->date_validite && now()->startOfDay()->isAfter($devis->date_validite->endOfDay())) {
                $devis->statut = 'expire';
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public static function genererNumero(): string
    {
        $annee = date('Y');
        $dernier = static::where('numero', 'like', "DEV-{$annee}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($dernier && preg_match('/DEV-\d{4}-(\d+)/', $dernier->numero, $matches)) {
            $prochain = (int) $matches[1] + 1;
        } else {
            $prochain = 1;
        }

        return sprintf('DEV-%s-%04d', $annee, $prochain);
    }
}
