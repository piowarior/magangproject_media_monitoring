<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeatmapData extends Model
{
    protected $fillable = [
        'region_id',
        'date',
        'intensity_score',
        'dominant_sentiment',
        'total_news',
    ];

    protected function casts(): array
    {
        return [
            'date'            => 'date',
            'intensity_score' => 'float',
        ];
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
