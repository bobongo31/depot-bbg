<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourrierExpedieCopy extends Model
{
    protected $table = 'courrier_expedie_copies';

    protected $fillable = [
        'courrier_expedie_id',
        'direction',
        'service',
    ];

    public function courrier()
    {
        return $this->belongsTo(CourrierExpedie::class, 'courrier_expedie_id');
    }
}