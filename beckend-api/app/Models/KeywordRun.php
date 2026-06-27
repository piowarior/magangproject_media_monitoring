<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


class KeywordRun extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'keyword_id',
        'started_at',
        'finished_at',
        'status',
    ];

    public function keyword()
    {
        return $this -> belongsTo(keyword::class);
    }
}
