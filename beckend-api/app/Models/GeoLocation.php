<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoLocation extends Model
{
    protected $fillable = ['region_id', 'lat', 'lng'];

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
        ];
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
