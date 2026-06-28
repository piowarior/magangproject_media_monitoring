<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_id', 'created_by',
        'title', 'period_start', 'period_end', 'status',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end'   => 'date',
        ];
    }

    public function keyword(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Berita yang masuk laporan ini
    public function news(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(News::class, 'report_items');
    }

    // Log export PDF/Excel laporan ini
    public function exportLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExportLog::class);
    }
}
