<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'type'];

    // Koordinat GPS wilayah ini
    public function geoLocation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(GeoLocation::class);
    }

    // Berita yang terkait wilayah ini
    public function news(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_regions');
    }

    // Data intensitas sentimen wilayah ini per hari
    public function heatmapData(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HeatmapData::class);
    }
}
