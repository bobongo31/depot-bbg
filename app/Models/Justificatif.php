<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justificatif extends Model
{
    use HasFactory;

    protected $fillable = ['depense_caisse_id', 'fichier'];

    public function depense()
    {
        return $this->belongsTo(DepenseCaisse::class, 'depense_caisse_id');
    }
}
