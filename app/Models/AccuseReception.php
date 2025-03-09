<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccuseReception extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement',
        'date_accuse_reception',
        'date_reception',
        'numero_reference',
        'nom_expediteur',
        'resume',
        'observation',
        'commentaires',
        'statut'
    ];

    // Relation avec les annexes
    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'accuse_de_reception_id');
    }
}
