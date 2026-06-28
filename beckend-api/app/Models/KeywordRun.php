<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_id',
        'triggered_by_user_id',
        'started_at',
        'finished_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at'  => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    // Relasi ke keyword induknya
    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // FIX: was keyword::class (lowercase = class not found)
        return $this->belongsTo(Keyword::class);
    }

    // Siapa user yang trigger crawl ini
    public function triggeredBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by_user_id');
    }

    // Scope helper
    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }
}
