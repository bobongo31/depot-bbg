<?php

// app/Models/DepenseCaisse.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepenseCaisse extends Model
{
    use HasFactory;

    protected $casts = [
        'date_depense' => 'datetime', // Cela va s'assurer que la colonne 'date_depense' est convertie en objet Carbon
    ];
    // Spécifier les attributs assignables en masse
    protected $fillable = [
        'rubrique',
        'montant',
        'date_depense',
        'description',
        'user_id',
    ];

    // Scope global pour filtrer par user_id
    protected static function booted()
    {
        static::addGlobalScope('user_id', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

        public function justificatifs()
    {
        return $this->hasMany(Justificatif::class);
    }

}
