<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom_redevable',
        'adresse',
        'telephone',
        'nom_taxateur',
        'nom_liquidateur',
        'matiere_taxable',
        'prix_à_payer',
    ];

    // Ajoutez d'autres méthodes ou relations si nécessaire
}
