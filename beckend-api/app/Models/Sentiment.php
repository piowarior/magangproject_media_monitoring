<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentiment extends Model
{
    protected $fillable = [
        'news_id',
        'final_sentiment',
        'confidence_score',
        'model_version',
    ];
}
