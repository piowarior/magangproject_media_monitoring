<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * GET /api/v1/reports
     * List laporan yang dibuat dari keyword milik user.
     */
    public function index(Request $request): JsonResponse
    {
        $userKeywordIds = $request->user()->keywords()->pluck('keywords.id');

        $reports = Report::with(['keyword', 'creator'])
            ->whereIn('keyword_id', $userKeywordIds)
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'id'           => $r->id,
                'title'        => $r->title,
                'keyword'      => $r->keyword?->keyword_text,
                'period_start' => $r->period_start->format('Y-m-d'),
                'period_end'   => $r->period_end->format('Y-m-d'),
                'status'       => $r->status,
                'created_by'   => $r->creator?->name,
                'created_at'   => $r->created_at->toISOString(),
            ]);

        return response()->json([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * POST /api/v1/reports
     * Buat laporan baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'keyword_id'   => ['required', 'integer', 'exists:keywords,id'],
            'period_start' => ['required', 'date'],
            'period_end'   => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        // Pastikan user subscribe keyword tersebut
        $isSubscribed = $request->user()->keywords()
            ->where('keyword_id', $validated['keyword_id'])
            ->exists();

        if (! $isSubscribed) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak subscribe keyword ini.',
            ], 403);
        }

        $report = Report::create([
            ...$validated,
            'created_by' => $request->user()->id,
            'status'     => 'draft',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat.',
            'data'    => [
                'id'           => $report->id,
                'title'        => $report->title,
                'period_start' => $report->period_start->format('Y-m-d'),
                'period_end'   => $report->period_end->format('Y-m-d'),
                'status'       => $report->status,
            ],
        ], 201);
    }

    /**
     * GET /api/v1/reports/{id}
     * Detail laporan + list berita di dalamnya.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $report = Report::with(['keyword', 'news.sentiment', 'news.source'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'           => $report->id,
                'title'        => $report->title,
                'keyword'      => $report->keyword?->keyword_text,
                'period_start' => $report->period_start->format('Y-m-d'),
                'period_end'   => $report->period_end->format('Y-m-d'),
                'status'       => $report->status,
                'news_count'   => $report->news->count(),
                'news'         => $report->news->map(fn ($n) => [
                    'id'        => $n->id,
                    'title'     => $n->title,
                    'url'       => $n->url,
                    'source'    => $n->source?->name,
                    'sentiment' => $n->sentiment?->final_sentiment ?? 'pending',
                    'published_at' => $n->published_at?->format('Y-m-d'),
                ]),
            ],
        ]);
    }
}
