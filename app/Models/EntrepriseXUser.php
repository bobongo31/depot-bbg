<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class EntrepriseXUser extends Authenticatable
{
    // Spécifie la connexion à la base de données 'entreprise_x_db'
    protected $connection = 'mysql_entreprise_x';

    // Par défaut, Laravel utilise 'users' comme table, mais vous pouvez spécifier la table si elle est différente
    protected $table = 'users';  // Vous pouvez ajuster le nom de la table ici si nécessaire

    // Propriétés supplémentaires que vous pouvez définir pour ce modèle si nécessaire
    protected $fillable = ['name', 'email', 'password'];
}
