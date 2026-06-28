<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sentiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'final_sentiment',
        'confidence_score',
        'model_version',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'float',
        ];
    }

    // Berita mana yang dianalisis
    public function news(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    // Helper: apakah sentimen ini positif?
    public function isPositive(): bool
    {
        return $this->final_sentiment === 'positive';
    }

    // Helper: apakah sentimen ini negatif?
    public function isNegative(): bool
    {
        return $this->final_sentiment === 'negative';
    }

    // Scope untuk filter cepat
    public function scopePositive($query)
    {
        return $query->where('final_sentiment', 'positive');
    }

    public function scopeNeutral($query)
    {
        return $query->where('final_sentiment', 'neutral');
    }

    public function scopeNegative($query)
    {
        return $query->where('final_sentiment', 'negative');
    }
}
