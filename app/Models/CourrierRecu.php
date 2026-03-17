<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierRecu extends Model
{
    // Définissez les relations ou autres propriétés si nécessaire
    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'accuse_de_reception_id');
    }
}
