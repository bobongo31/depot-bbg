<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Reponse extends Model
{
    use HasFactory;

    /**
     * Champ autorisés en écriture
     */
    protected $fillable = [
        'numero_enregistrement',
        'numero_reference',
        'service_concerne',
        'commentaires',
        'telegramme_id',
        'archive',
        'status_archive',
        'user_id', // à ne pas oublier si relation avec User
    ];

    /**
     * Ajout d’un scope global pour filtrer selon l’entreprise de l’utilisateur connecté.
     */
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

    /**
     * Relation avec l’utilisateur propriétaire de la réponse.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les annexes.
     */
    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'reponse_id');
    }

    /**
     * Relation avec le télégramme.
     */
    public function telegramme()
    {
        return $this->belongsTo(Telegramme::class, 'telegramme_id');
    }
}
