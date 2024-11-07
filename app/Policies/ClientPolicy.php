<?php

namespace App\Policies;

use App\Models\User;

class ClientPolicy
{
    /**
     * Détermine si l'utilisateur peut créer un redevable.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('read_write'); // Vérifie si l'utilisateur a le rôle "read_write"
    }
}
