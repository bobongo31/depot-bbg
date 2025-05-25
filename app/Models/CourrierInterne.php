<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CourrierInterne extends Model
{
    protected $fillable = [
        'numero_enregistrement',
        'date_envoi',
        'service_expediteur_id',
        'service_destinataire_id',
        'date_limite_reponse',
        'statut',
        'commentaire'
    ];

    public function serviceExpediteur()
    {
        return $this->belongsTo(Service::class, 'service_expediteur_id');
    }

    public function serviceDestinataire()
    {
        return $this->belongsTo(Service::class, 'service_destinataire_id');
    }

    public function reponses()
    {
        return $this->hasMany(ReponseCourrierInterne::class);
    }
}
