<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DossierPersonnel extends Model
{
    protected $table = 'dossiers_personnels'; // Ajoute cette ligne pour indiquer explicitement la table
    protected $fillable = [
        'user_id', 'agent_id', 'poste', 'date_embauche', 'matricule', 'contrat_type', 'notes'
    ];

    protected static function booted()
    {
        static::addGlobalScope('proprietaire', function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function annexes()
{
    return $this->hasMany(AnnexeDossierPersonnel::class);
}

}

