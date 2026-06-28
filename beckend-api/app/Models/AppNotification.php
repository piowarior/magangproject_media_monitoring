<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Nama class AppNotification agar tidak bentrok dengan
// Illuminate\Notifications\Notification bawaan Laravel
class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'is_read', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope: notif yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Helper: tandai sudah dibaca
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
