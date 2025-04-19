<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AccuseReception extends Model
{



    protected static function booted()
    {
        static::addGlobalScope('entreprise', function (Builder $builder) {
            if (Auth::check()) {
                $builder->whereHas('user', function ($query) {
                    $query->where('entreprise', Auth::user()->entreprise);
                });
            }
        });
    }
    // Affichage de la liste des accusés de réception
    public function indexAccuses()
    {
        $accuses = AccuseReception::all(); // Récupérer les accusés de réception
        return view('accuses.index', compact('accuses'));
    }
    use HasFactory;

    protected $fillable = [
        'numero_enregistrement',
        'date_accuse_reception',
        'date_reception',
        'receptionne_par',
        'numero_reference',
        'nom_expediteur',
        'resume',
        'observation',
        'commentaires',
        'statut',
        'archive',
        'status_archive',
    ];

    // Relation avec les annexes
    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'accuse_de_reception_id');
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

}
