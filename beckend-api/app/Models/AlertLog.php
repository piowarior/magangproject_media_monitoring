<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model
{
    protected $fillable = ['alert_rule_id', 'triggered_at', 'detail'];

    protected function casts(): array
    {
        return ['triggered_at' => 'datetime'];
    }

    public function alertRule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AlertRule::class);
    }
}
