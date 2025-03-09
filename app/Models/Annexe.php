<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annexe extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path', // Le chemin du fichier stocké
        'accuse_de_reception_id', // Clé étrangère vers le modèle AccuseDeReception
    ];

    // Relation inverse avec le modèle AccuseDeReception
    public function accuseDeReception()
    {
        return $this->belongsTo(AccuseDeReception::class);
    }
}
