<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportLog extends Model
{
    protected $fillable = [
        'report_id', 'exported_by', 'format', 'file_path', 'exported_at',
    ];

    protected function casts(): array
    {
        return ['exported_at' => 'datetime'];
    }

    public function report(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function exporter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}
