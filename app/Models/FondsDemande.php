<?php
// app/Models/FondsDemande.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FondsDemande extends Model
{
    use HasFactory;

    // Spécifier les attributs assignables en masse
    protected $fillable = [
        'montant',
        'motif',
        'statut',
        'user_id',
    ];

    // Scope global pour filtrer par user_id
    protected static function booted()
    {
        static::addGlobalScope('user_id', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }
}
