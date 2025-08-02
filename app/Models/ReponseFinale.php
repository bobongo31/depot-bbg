<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReponseFinale extends Model
{
    use HasFactory;

    // Indique explicitement le nom de la table dans la base
    protected $table = 'reponses_finales';

    protected $fillable = [
        'numero_enregistrement',
        'numero_reference',
        'service_concerne',
        'observation',
        'reponse_id',
        'telegramme_id',
        'user_id',
    ];

    public function reponse()
    {
        return $this->belongsTo(Reponse::class, 'reponse_id');

    }

    

    public function telegramme()
    {
        return $this->belongsTo(Telegramme::class, 'telegramme_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'reponse_finale_id');
    }

}
