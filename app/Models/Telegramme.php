<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\AccuseReception;


class Telegramme extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement',
        'numero_reference',
        'service_concerne',
        'observation',
        'commentaires',
        'archive',
        'status_archive',
        'user_id', // 👈 Assure que c'est assignable
        'document_path', // <-- ajouté ici
        'statut',
    ];

    /**
     * Scope global pour filtrer selon l’entreprise de l’utilisateur connecté.
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



            public function accuseReception()
        {
            return $this->hasOne(
                AccuseReception::class,
                'numero_enregistrement',   // clé étrangère dans accuse_receptions
                'numero_enregistrement'    // clé locale dans telegrammes
            );
        }

            public function getStatutAttribute()
    {
        return optional($this->accuseReception)->statut;
    }

        public function getStatutFinalAttribute()
        {
            if ($this->relationLoaded('accuseReception') && $this->accuseReception) {
                return $this->accuseReception->statut;
            }

            return $this->statut; // fallback
        }


    /**
     * Relation avec l’utilisateur propriétaire du télégramme.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'telegramme_id');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'telegramme_id');
    }
}
