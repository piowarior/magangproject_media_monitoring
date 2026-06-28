<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
        'user_id', 'ip_address', 'device', 'user_agent', 'status', 'login_time',
    ];

    protected function casts(): array
    {
        return ['login_time' => 'datetime'];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
