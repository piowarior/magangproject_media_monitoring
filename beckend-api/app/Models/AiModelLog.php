<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModelLog extends Model
{
    protected $fillable = [
        'news_id',
        'model_a_score',
        'model_b_score',
        'model_c_score',
        'final_score',
        'processing_time_ms',
    ];

    protected function casts(): array
    {
        return [
            'model_a_score' => 'float',
            'model_b_score' => 'float',
            'model_c_score' => 'float',
            'final_score'   => 'float',
        ];
    }

    public function news(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
