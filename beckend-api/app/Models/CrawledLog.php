<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrawledLog extends Model
{
    protected $fillable = [
        'keyword_id',
        'status',
        'total_fetched',
        'total_saved',
        'error_message',
    ];
}
