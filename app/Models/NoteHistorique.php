<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteHistorique extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'type', 'contenu', 'prochaine_action'];

    protected $casts = ['prochaine_action' => 'date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
