<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'type',
    ];

    // Semua berita yang bersumber dari media ini
    public function news(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class, 'source_id');
    }

    // Ranking media ini berdasarkan sentimen
    public function rankings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MediaRanking::class, 'source_id');
    }
}
