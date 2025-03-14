<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement', 'numero_reference', 'service_concerne', 'commentaires', 'telegramme_id',
    ];

    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'reponse_id');
    }

    public function telegramme()
    {
        return $this->belongsTo(Telegramme::class, 'telegramme_id');
    }
}
