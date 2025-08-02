<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Reponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement',
        'numero_reference',
        'service_concerne',
        'commentaires',
        'telegramme_id',
        'archive',
        'status_archive',
        'user_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('entreprise', function (Builder $builder) {
            if (Auth::check()) {
                $builder->whereHas('user', function (Builder $query) {
                    $query->where('entreprise', Auth::user()->entreprise);
                });
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'reponse_id');
    }

    public function telegramme()
    {
        return $this->belongsTo(Telegramme::class, 'telegramme_id');
    }

    // Relation vers la réponse finale (table reponses_finales)
    public function reponseFinale()
    {
        return $this->hasOne(ReponseFinale::class, 'reponse_id');
    }

    public function reponseParent()
    {
        return $this->belongsTo(Reponse::class, 'reponse_id');
    }
}
