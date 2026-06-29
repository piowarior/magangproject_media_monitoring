<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'reliability_score',
        'total_news',
        'positive_count',
        'neutral_count',
        'negative_count',
        'is_active',
        'priority',
        'last_crawled_at',
        'notes',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'last_crawled_at'    => 'datetime',
        'reliability_score'  => 'decimal:2',
    ];

    /**
     * Semua berita yang bersumber dari media ini.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'source_id');
    }

    /**
     * Ranking media ini berdasarkan periode.
     */
    public function rankings(): HasMany
    {
        return $this->hasMany(MediaRanking::class, 'source_id');
    }

    /**
     * Cari atau buat entri media berdasarkan nama.
     * Dipanggil oleh Crawler saat menemukan media baru.
     */
    public static function findOrCreateByName(string $name, string $domain = null): self
    {
        return self::firstOrCreate(
            ['name' => $name],
            [
                'domain'          => $domain,
                'is_active'       => true,
                'last_crawled_at' => now(),
            ]
        );
    }

    /**
     * Update cached stats dari sentimen berita.
     * Dipanggil setelah AI selesai analisis.
     */
    public function recalculateStats(): void
    {
        $stats = $this->news()
            ->join('sentiments', 'news.id', '=', 'sentiments.news_id')
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN sentiments.final_sentiment = ? THEN 1 ELSE 0 END) as pos,
                SUM(CASE WHEN sentiments.final_sentiment = ? THEN 1 ELSE 0 END) as neu,
                SUM(CASE WHEN sentiments.final_sentiment = ? THEN 1 ELSE 0 END) as neg
            ', ['positive', 'neutral', 'negative'])
            ->first();

        $this->update([
            'total_news'      => $stats->total ?? 0,
            'positive_count'  => $stats->pos   ?? 0,
            'neutral_count'   => $stats->neu   ?? 0,
            'negative_count'  => $stats->neg   ?? 0,
            'last_crawled_at' => now(),
        ]);
    }

    /**
     * Persentase sentimen negatif.
     */
    public function getNegativePctAttribute(): float
    {
        if ($this->total_news === 0) return 0;
        return round(($this->negative_count / $this->total_news) * 100, 1);
    }

    /**
     * Persentase sentimen positif.
     */
    public function getPositivePctAttribute(): float
    {
        if ($this->total_news === 0) return 0;
        return round(($this->positive_count / $this->total_news) * 100, 1);
    }
}
