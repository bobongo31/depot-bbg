<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Message extends Model {
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',   // <- nom correct du champ texte
        'is_read',   // <- si tu veux aussi l’assigner
    ];

    protected static function booted()
    {
        static::addGlobalScope('entreprise', function (Builder $builder) {
            if (Auth::check()) {
                $builder->whereHas('sender', function ($query) {
                    $query->where('entreprise', Auth::user()->entreprise);
                });
            }
        });
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function annexes() {
        return $this->hasMany(AnnexeMessage::class, 'message_id');
    }
}
