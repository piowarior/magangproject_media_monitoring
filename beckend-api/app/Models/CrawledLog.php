<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrawledLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_id',
        'status',
        'total_fetched',
        'total_saved',
        'error_message', // FIX: was 'erorr_message'
    ];

    // Keyword yang menghasilkan log ini
    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }

    // Scope untuk filter cepat
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'fail');
    }
}
