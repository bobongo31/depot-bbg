<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemBackup extends Model
{
    protected $fillable = [
        'type',
        'status',
        'disk',
        'path',
        'filename',
        'size_bytes',
        'started_at',
        'finished_at',
        'duration_ms',
        'triggered_by',
        'notes',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function getSizeHumanAttribute(): string
    {
        $bytes = (int) $this->size_bytes;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2, ',', ' ') . ' Go';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2, ',', ' ') . ' Mo';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2, ',', ' ') . ' Ko';
        }

        return $bytes . ' o';
    }
}