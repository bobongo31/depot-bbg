<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnexeDossierPersonnel extends Model
{
    use HasFactory;

    protected $table = 'annexes_dossier_personnel';

    public function dossierPersonnel()
    {
        return $this->belongsTo(DossierPersonnel::class);
    }
}
