<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStat extends Model
{
    protected $fillable = [
        'keyword_id', 'date',
        'total_news', 'positive', 'neutral', 'negative',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}
