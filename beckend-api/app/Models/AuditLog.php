<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    // Hanya created_at, tidak perlu updated_at
    public $timestamps  = false;
    public $incrementing = true;

    protected $fillable = [
        'user_id', 'action', 'table_name', 'record_id', 'description', 'ip_address',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
