<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    // Autorise l'affectation en masse pour ces champs
    protected $fillable = ['name', 'database'];
}
