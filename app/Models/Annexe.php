<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annexe extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path', 'accuse_de_reception_id', 'reponse_id', 'telegramme_id',
    ];

    public function accuseDeReception()
    {
        return $this->belongsTo(AccuseDeReception::class, 'accuse_de_reception_id');
    }

    public function reponse()
    {
        return $this->belongsTo(Reponse::class, 'reponse_id');
    }

    public function telegramme()
    {
        return $this->belongsTo(Telegramme::class, 'telegramme_id');
    }
}
