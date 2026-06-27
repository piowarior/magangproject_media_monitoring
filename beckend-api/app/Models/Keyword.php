<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_text',
        'region_scope',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_keywords');
    }

    public function runs()
    {
        return $this->hasMany(KeywordRun::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}

