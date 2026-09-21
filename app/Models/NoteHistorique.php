<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteHistorique extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'type', 'contenu', 'prochaine_action'];

    protected $casts = ['prochaine_action' => 'date'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
