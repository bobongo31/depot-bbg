<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// App\Models\User.php

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', // Ajout de 'role'
    ];

    protected $hidden = [
        'password', 'remember_token', 'service',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Vérifier si l'utilisateur a un rôle particulier.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur a un rôle administrateur.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin'); // Exemple avec 'admin' comme rôle
    }

    // Autres méthodes spécifiques aux rôles peuvent être ajoutées ici
}
