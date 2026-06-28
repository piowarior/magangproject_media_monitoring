<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaRanking extends Model
{
    protected $fillable = [
        'source_id', 'period_date',
        'total_news', 'positive_ratio', 'negative_ratio', 'score',
    ];

    protected function casts(): array
    {
        return [
            'period_date'    => 'date',
            'positive_ratio' => 'float',
            'negative_ratio' => 'float',
            'score'          => 'float',
        ];
    }

    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }
}
