<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory; // Ajout du trait HasFactory pour les tests et les migrations

    protected $fillable = [
        'nom_redevable',
        'adresse',
        'telephone',
        'nom_taxateur',
        'nom_liquidateur',
        'matiere_taxable',
        'prix_matiere',
        'prix_a_payer',
    ];

    // Relation entre Client et Paiement
    public function paiements()
    {
        return $this->hasMany(Paiement::class); // Un client peut avoir plusieurs paiements
    }
}
