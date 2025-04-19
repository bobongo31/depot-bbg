<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeConge extends Model
{
    use HasFactory;

    protected $table = 'demandes_conges'; // Spécifie le nom de la table

    protected $fillable = [
        'agent_id',
        'type_conge',
        'date_debut',
        'date_fin',
        'motif',
        'statut',
        'user_id',
    ];

    // Relation avec l'agent (User)
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Relation avec l'utilisateur ayant créé la demande
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope global pour filtrer par user_id
    protected static function booted()
    {
        static::addGlobalScope('user', function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }
}
