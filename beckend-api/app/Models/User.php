<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    // Keyword yang di-subscribe user ini (via pivot user_keywords)
    public function keywords(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'user_keywords')
                    ->withTimestamps();
    }

    // Crawl yang di-trigger user ini
    public function keywordRuns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KeywordRun::class, 'triggered_by_user_id');
    }

    // Notifikasi untuk user ini
    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AppNotification::class, 'user_id');
    }

    // Log audit aksi user ini
    public function auditLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Log login user ini
    public function loginLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LoginLog::class);
    }
}
