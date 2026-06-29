<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KeywordGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'created_by'];

    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'keyword_group_items');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
