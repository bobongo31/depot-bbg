<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telegramme extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement', 'numero_reference', 'service_concerne', 'observation', 'commentaires','archive', 'status_archive',
    ];

    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'telegramme_id');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'telegramme_id');
    }
}
