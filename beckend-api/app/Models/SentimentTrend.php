<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentTrend extends Model
{
    protected $fillable = ['keyword_id', 'date', 'sentiment_distribution'];

    protected function casts(): array
    {
        return [
            'date'                    => 'date',
            'sentiment_distribution'  => 'array', // auto JSON decode
        ];
    }

    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}
