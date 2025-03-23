<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement', 'numero_reference', 'resume', 'service_concerne', 'commentaires', 'statut', 'categorie'
    ];
    
}
