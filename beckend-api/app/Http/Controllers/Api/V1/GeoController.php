<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\GeoLocation;
use App\Models\HeatmapData;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    /**
     * GET /api/v1/geo/regions
     * List semua wilayah Banten + koordinat GPS.
     */
    public function regions(): JsonResponse
    {
        $regions = Region::with('geoLocation')
            ->get()
            ->map(fn ($r) => [
                'id'   => $r->id,
                'name' => $r->name,
                'slug' => $r->slug,
                'type' => $r->type,
                'lat'  => $r->geoLocation?->lat,
                'lng'  => $r->geoLocation?->lng,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $regions,
        ]);
    }

    /**
     * GET /api/v1/geo/heatmap
     * Data intensitas sentimen per wilayah untuk peta.
     */
    public function heatmap(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->toDateString());

        $heatmap = HeatmapData::with(['region.geoLocation'])
            ->where('date', $date)
            ->get()
            ->map(fn ($h) => [
                'region_id'          => $h->region_id,
                'region_name'        => $h->region?->name,
                'lat'                => $h->region?->geoLocation?->lat,
                'lng'                => $h->region?->geoLocation?->lng,
                'intensity_score'    => $h->intensity_score,
                'dominant_sentiment' => $h->dominant_sentiment,
                'total_news'         => $h->total_news,
            ]);

        return response()->json([
            'success' => true,
            'date'    => $date,
            'data'    => $heatmap,
        ]);
    }
}
