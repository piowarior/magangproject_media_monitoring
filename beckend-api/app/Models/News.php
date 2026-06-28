<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_id',
        'source_id',
        'title',
        'content',
        'url',
        'published_at',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    // ─── Relasi ke Atas ────────────────────────────────────────────────────────

    // Berita ini berasal dari keyword apa
    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }

    // Berita ini dari sumber media mana
    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }

    // ─── Relasi ke Bawah ───────────────────────────────────────────────────────

    // Hasil analisis sentimen AI
    public function sentiment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Sentiment::class);
    }

    // Log skor tiap model AI (untuk debugging ensemble)
    public function aiModelLog(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AiModelLog::class);
    }

    // ─── Relasi Many-to-Many ───────────────────────────────────────────────────

    // Topik berita ini (Politik, Ekonomi, dll)
    public function topics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'news_topics');
    }

    // Entitas yang disebut dalam berita (nama orang, instansi)
    public function entities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'news_entities');
    }

    // Wilayah Banten yang relevan dengan berita ini
    public function regions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'news_regions');
    }

    // Laporan yang menyertakan berita ini
    public function reports(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'report_items');
    }
}
