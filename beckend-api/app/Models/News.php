<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'keyword_id',
        'source_id',
        'title',
        'content',
        'url',
        'published_at',
        'hash',
    ];
}
