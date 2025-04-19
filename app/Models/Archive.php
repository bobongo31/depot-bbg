<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement',
        'numero_reference',
        'resume',
        'service_concerne',
        'commentaires',
        'statut',
        'categorie',
        'user_id', // important pour la relation avec l'utilisateur
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
     * Relation avec l’utilisateur propriétaire de l’archive.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
