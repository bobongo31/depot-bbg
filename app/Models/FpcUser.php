<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FpcUser extends Authenticatable
{
    // Spécifie la connexion à la base de données 'gestion_courrier_fpc'
    protected $connection = 'gestion_courrier';

    // Par défaut, Laravel utilise 'users' comme table, mais vous pouvez spécifier la table si elle est différente
    protected $table = 'users';  // Vous pouvez ajuster le nom de la table ici si nécessaire

    // Propriétés supplémentaires que vous pouvez définir pour ce modèle si nécessaire
    protected $fillable = ['name', 'email', 'password'];
}
