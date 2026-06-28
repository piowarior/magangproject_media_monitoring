<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DailyStat;
use App\Models\News;
use App\Models\Sentiment;
use App\Models\MediaRanking;
use App\Models\NewsSource;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * GET /api/v1/dashboard/stats
     * Ringkasan statistik utama untuk home screen.
     */
    public function stats(Request $request): JsonResponse
    {
        $userKeywordIds = $request->user()->keywords()->pluck('keywords.id');
        $today = now()->toDateString();

        // Total berita hari ini dari keyword user
        $todayCount = News::whereIn('keyword_id', $userKeywordIds)
            ->whereDate('published_at', $today)
            ->count();

        // Total berita 7 hari terakhir
        $weekCount = News::whereIn('keyword_id', $userKeywordIds)
            ->where('published_at', '>=', now()->subDays(7))
            ->count();

        // Breakdown sentimen dari keyword user
        $sentimentBreakdown = Sentiment::whereHas('news', fn ($q) =>
            $q->whereIn('keyword_id', $userKeywordIds)
        )
        ->selectRaw("final_sentiment, COUNT(*) as count")
        ->groupBy('final_sentiment')
        ->pluck('count', 'final_sentiment');

        $totalSentiment = $sentimentBreakdown->sum();

        return response()->json([
            'success' => true,
            'data'    => [
                'today_news'   => $todayCount,
                'week_news'    => $weekCount,
                'sentiment'    => [
                    'positive' => (int) ($sentimentBreakdown['positive'] ?? 0),
                    'neutral'  => (int) ($sentimentBreakdown['neutral']  ?? 0),
                    'negative' => (int) ($sentimentBreakdown['negative'] ?? 0),
                    'total'    => (int) $totalSentiment,
                    'positive_pct' => $totalSentiment > 0
                        ? round(($sentimentBreakdown['positive'] ?? 0) / $totalSentiment * 100, 1)
                        : 0,
                    'negative_pct' => $totalSentiment > 0
                        ? round(($sentimentBreakdown['negative'] ?? 0) / $totalSentiment * 100, 1)
                        : 0,
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/dashboard/chart
     * Data grafik tren sentimen 7 hari terakhir.
     */
    public function chart(Request $request): JsonResponse
    {
        $userKeywordIds = $request->user()->keywords()->pluck('keywords.id');
        $days = $request->integer('days', 7);

        // Ambil data daily stats dari keyword user
        $stats = DailyStat::whereIn('keyword_id', $userKeywordIds)
            ->where('date', '>=', now()->subDays($days)->toDateString())
            ->selectRaw("date, SUM(positive) as positive, SUM(neutral) as neutral, SUM(negative) as negative, SUM(total_news) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $stats->map(fn ($s) => [
                'date'     => $s->date->format('Y-m-d'),
                'positive' => (int) $s->positive,
                'neutral'  => (int) $s->neutral,
                'negative' => (int) $s->negative,
                'total'    => (int) $s->total,
            ]),
        ]);
    }

    /**
     * GET /api/v1/dashboard/top-media
     * Top 5 media berdasarkan jumlah berita dari keyword user.
     */
    public function topMedia(Request $request): JsonResponse
    {
        $userKeywordIds = $request->user()->keywords()->pluck('keywords.id');

        $topMedia = News::with('source')
            ->whereIn('keyword_id', $userKeywordIds)
            ->select('source_id', DB::raw('COUNT(*) as news_count'))
            ->groupBy('source_id')
            ->orderByDesc('news_count')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'source_id'  => $item->source_id,
                'name'       => $item->source?->name,
                'news_count' => $item->news_count,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $topMedia,
        ]);
    }

    /**
     * GET /api/v1/dashboard/top-issues
     * Top 5 isu (topik) paling banyak dibahas.
     */
    public function topIssues(Request $request): JsonResponse
    {
        $userKeywordIds = $request->user()->keywords()->pluck('keywords.id');

        $topIssues = Topic::withCount(['news' => fn ($q) =>
            $q->whereIn('keyword_id', $userKeywordIds)
        ])
        ->having('news_count', '>', 0)
        ->orderByDesc('news_count')
        ->limit(5)
        ->get()
        ->map(fn ($t) => [
            'id'         => $t->id,
            'name'       => $t->name,
            'color'      => $t->color,
            'news_count' => $t->news_count,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $topIssues,
        ]);
    }
}
