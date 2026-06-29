<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * GET /api/v1/news
     * List berita dengan filter keyword, sentimen, tanggal.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword_id'   => ['nullable', 'integer', 'exists:keywords,id'],
            'sentiment'    => ['nullable', 'in:positive,neutral,negative'],
            'source_id'    => ['nullable', 'integer', 'exists:news_sources,id'],
            'date_from'    => ['nullable', 'date'],
            'date_to'      => ['nullable', 'date', 'after_or_equal:date_from'],
            'search'       => ['nullable', 'string', 'max:255'],
            'per_page'     => ['nullable', 'integer', 'min:5', 'max:50'],
        ]);

        // Ambil keyword yang di-subscribe user (untuk filter default)
        $userKeywordIds = $request->user()->keywords()->pluck('keywords.id');

        $query = News::with(['source', 'keyword', 'sentiment'])
            ->when(
                $request->filled('keyword_id'),
                fn ($q) => $q->where('keyword_id', $validated['keyword_id']),
                // Default: hanya tampilkan berita dari keyword milik user
                fn ($q) => $q->whereIn('keyword_id', $userKeywordIds)
            )
            ->when(
                $request->filled('sentiment'),
                fn ($q) => $q->whereHas('sentiment', fn ($sq) =>
                    $sq->where('final_sentiment', $validated['sentiment'])
                )
            )
            ->when(
                $request->filled('source_id'),
                fn ($q) => $q->where('source_id', $validated['source_id'])
            )
            ->when(
                $request->filled('date_from'),
                fn ($q) => $q->whereDate('published_at', '>=', $validated['date_from'])
            )
            ->when(
                $request->filled('date_to'),
                fn ($q) => $q->whereDate('published_at', '<=', $validated['date_to'])
            )
            ->when(
                $request->filled('search'),
                fn ($q) => $q->where('title', 'ILIKE', '%' . $validated['search'] . '%')
            )
            ->latest('published_at');

        $perPage = $validated['per_page'] ?? 15;
        $paginated = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $paginated->map(fn ($news) => $this->formatNews($news)),
            'meta'    => [
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/news/{id}
     * Detail berita + hasil analisis AI.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $news = News::with(['source', 'keyword', 'sentiment', 'aiModelLog', 'topics', 'entities', 'regions'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'           => $news->id,
                'title'        => $news->title,
                'content'      => $news->content,
                'url'          => $news->url,
                'published_at' => $news->published_at?->toISOString(),
                'source'       => ['id' => $news->source?->id, 'name' => $news->source?->name],
                'keyword'      => ['id' => $news->keyword?->id, 'text' => $news->keyword?->keyword_text],
                'sentiment'    => $news->sentiment ? [
                    'label'            => $news->sentiment->final_sentiment,
                    'confidence_score' => $news->sentiment->confidence_score,
                    'model_version'    => $news->sentiment->model_version,
                ] : null,
                'ai_scores'    => $news->aiModelLog ? [
                    'model_a' => $news->aiModelLog->model_a_score,
                    'model_b' => $news->aiModelLog->model_b_score,
                    'model_c' => $news->aiModelLog->model_c_score,
                    'final'   => $news->aiModelLog->final_score,
                    'time_ms' => $news->aiModelLog->processing_time_ms,
                ] : null,
                'topics'       => $news->topics->pluck('name'),
                'entities'     => $news->entities->map(fn ($e) => ['name' => $e->name, 'type' => $e->type]),
                'regions'      => $news->regions->pluck('name'),
            ],
        ]);
    }

    private function formatNews(News $news): array
    {
        return [
            'id'           => $news->id,
            'title'        => $news->title,
            'url'          => $news->url,
            'published_at' => $news->published_at?->toISOString(),
            'source'       => $news->source?->name,
            'keyword'      => $news->keyword?->keyword_text,
            'sentiment'    => $news->sentiment?->final_sentiment ?? 'pending',
        ];
    }
}
