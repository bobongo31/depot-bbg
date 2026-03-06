<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourrierExpedie extends Model
{
    use HasFactory;

    protected $table = 'courrier_expedies';

    protected $fillable = [
        'user_id',

        // Registre
        'numero_ordre',        // N° manuel
        'date_expedition',

        // Courrier
        'numero_lettre',
        'destinataire',
        'resume',
        'observation',

        // Annexes (upload chunk)
        'annexes',
    ];

    protected $casts = [
        'date_expedition' => 'date',
        'annexes'         => 'array',
    ];

    /* =========================
     | Relations
     ========================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* =========================
     | Helpers
     ========================= */

    /**
     * Retourne le nombre d'annexes
     */
    public function annexesCount(): int
    {
        return is_array($this->annexes) ? count($this->annexes) : 0;
    }

    /**
     * Vérifie si le courrier a des annexes
     */
    public function hasAnnexes(): bool
    {
        return $this->annexesCount() > 0;
    }
    public function copies()
{
    return $this->hasMany(CourrierExpedieCopy::class);
}

}
