<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyStat extends Model
{
    protected $fillable = [
        'keyword_id', 'week_start', 'week_end',
        'total_news', 'positive', 'neutral', 'negative', 'summary',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'week_end'   => 'date',
        ];
    }

    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}
