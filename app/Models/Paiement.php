<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements'; // Assurez-vous que le nom de la table est défini

    protected $fillable = [
        'client_id',          // Ajouté client_id pour permettre l'association avec le client
        'matieres_taxables',
        'prix_matiere',
        'prix_a_payer',
        'date_ordonancement',
        'date_accuse_reception',
        'cout_opportunite',
        'date_paiement',
        'retard_de_paiement',
        'nom_ordonanceur',
        'status',            // Ajoutez status pour le champ de statut
    ];

    /**
     * Relation avec le modèle Client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id'); // 'client_id' doit correspondre à votre clé étrangère
    }
}
