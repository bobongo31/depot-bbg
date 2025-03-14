<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ReponseCourrierInterne extends Model
{
    protected $fillable = [
        'courrier_interne_id',
        'service_repondant_id',
        'date_reponse',
        'reponse',
        'annexes'
    ];

    protected $casts = [
        'annexes' => 'array',
    ];

    public function courrierInterne()
    {
        return $this->belongsTo(CourrierInterne::class);
    }

    public function serviceRepondant()
    {
        return $this->belongsTo(Service::class, 'service_repondant_id');
    }

    public function annexes()
    {
        return $this->hasMany(Annexe::class);
    }
}
