<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\UserKeyword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KeywordController extends Controller
{
    /**
     * GET /api/v1/keywords
     * List keyword yang di-subscribe oleh user yang login.
     */
    public function index(Request $request): JsonResponse
    {
        $keywords = $request->user()
            ->keywords()
            ->withCount('news')
            ->latest('user_keywords.created_at')
            ->get()
            ->map(fn ($k) => [
                'id'           => $k->id,
                'keyword_text' => $k->keyword_text,
                'region_scope' => $k->region_scope,
                'status'       => $k->status,
                'news_count'   => $k->news_count,
                'subscribed_at'=> $k->pivot->created_at,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $keywords,
        ]);
    }

    /**
     * POST /api/v1/keywords
     * Subscribe keyword baru (atau pakai existing jika sudah ada).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword_text' => ['required', 'string', 'max:255'],
            'region_scope' => ['nullable', 'string', 'max:100'],
        ]);

        // Cari keyword yang sama (case-insensitive), buat baru jika tidak ada
        $keyword = Keyword::firstOrCreate(
            ['keyword_text' => $validated['keyword_text']],
            [
                'region_scope' => $validated['region_scope'] ?? 'Banten',
                'status'       => 'active',
            ]
        );

        // Subscribe user ke keyword ini (jika belum)
        $user = $request->user();
        if (! $user->keywords()->where('keyword_id', $keyword->id)->exists()) {
            $user->keywords()->attach($keyword->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Keyword berhasil ditambahkan.',
            'data'    => [
                'id'           => $keyword->id,
                'keyword_text' => $keyword->keyword_text,
                'region_scope' => $keyword->region_scope,
                'status'       => $keyword->status,
            ],
        ], 201);
    }

    /**
     * DELETE /api/v1/keywords/{id}
     * Unsubscribe user dari keyword (keyword-nya tidak dihapus).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        // Cek apakah user subscribe keyword ini
        if (! $user->keywords()->where('keyword_id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword tidak ditemukan di daftar Anda.',
            ], 404);
        }

        $user->keywords()->detach($id);

        return response()->json([
            'success' => true,
            'message' => 'Keyword berhasil dihapus dari daftar Anda.',
        ]);
    }

    /**
     * POST /api/v1/keywords/{id}/analyze
     * Trigger crawling manual untuk keyword tertentu.
     */
    public function analyze(Request $request, int $id): JsonResponse
    {
        $keyword = $request->user()->keywords()->find($id);

        if (! $keyword) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword tidak ditemukan.',
            ], 404);
        }

        // Buat record keyword_run dengan status 'processing'
        $run = $keyword->runs()->create([
            'triggered_by_user_id' => $request->user()->id,
            'started_at'           => now(),
            'status'               => 'processing',
        ]);

        // TODO: dispatch job crawling ke queue (akan diimplementasi di FASE 7)
        // CrawlKeywordJob::dispatch($keyword, $run);

        return response()->json([
            'success' => true,
            'message' => 'Crawling dijadwalkan. Harap tunggu beberapa menit.',
            'data'    => [
                'run_id'     => $run->id,
                'keyword'    => $keyword->keyword_text,
                'started_at' => $run->started_at,
                'status'     => $run->status,
            ],
        ]);
    }
}
