<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'keyword_text',
        'region_scope',
        'status',
    ];

    // ─── Relasi ke User ────────────────────────────────────────────────────────

    // User yang subscribe keyword ini (many-to-many via user_keywords)
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_keywords')
                    ->withTimestamps();
    }

    // ─── Relasi ke Crawling ────────────────────────────────────────────────────

    // History setiap kali keyword ini di-crawl
    public function runs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KeywordRun::class);
    }

    // Log debug crawling
    public function crawledLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CrawledLog::class);
    }

    // ─── Relasi ke Berita ──────────────────────────────────────────────────────

    // Semua berita yang ditemukan dari keyword ini
    public function news(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class);
    }

    // ─── Relasi ke Analytics ───────────────────────────────────────────────────

    public function dailyStats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DailyStat::class);
    }

    public function weeklyStats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WeeklyStat::class);
    }

    public function sentimentTrends(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SentimentTrend::class);
    }

    // ─── Relasi ke Laporan & Alert ─────────────────────────────────────────────

    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function alertRules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AlertRule::class);
    }

    // ─── Scope helpers ─────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
