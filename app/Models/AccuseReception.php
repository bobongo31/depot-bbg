<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AccuseReception extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement',
        'date_accuse_reception',
        'date_reception',
        'receptionne_par',
        'numero_reference',
        'nom_expediteur',
        'resume',
        'objet', // ✅ Champ ajouté ici
        'observation',
        'commentaires',
        'statut',
        'archive',
        'status_archive',
        'user_id', // Ajouté pour lier l'accusé à l'utilisateur
    ];

    /**
     * Relation avec les annexes.
     */
    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'accuse_de_reception_id');
    }

    /**
     * Relation avec l'utilisateur (propriétaire de l'accusé).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope global pour filtrer les accusés selon l'entreprise de l'utilisateur connecté.
     */
    protected static function booted()
    {
        static::addGlobalScope('entreprise', function (Builder $builder) {
            if (Auth::check()) {
                $builder->whereHas('user', function ($query) {
                    $query->where('entreprise', Auth::user()->entreprise);
                });
            }
        });

        // Remplir automatiquement le user_id à la création
        static::creating(function ($accuse) {
            if (Auth::check() && empty($accuse->user_id)) {
                $accuse->user_id = Auth::id();
            }
        });
    }

    /**
     * Exemple de méthode pour afficher les accusés (non obligatoire dans le modèle).
     */
    public function indexAccuses()
    {
        $accuses = AccuseReception::all(); // Déjà filtrés automatiquement par entreprise
        return view('accuses.index', compact('accuses'));
    }
}
