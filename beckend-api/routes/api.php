<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\GeoController;
use App\Http\Controllers\Api\V1\KeywordController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Media Monitoring DPRD Banten
|--------------------------------------------------------------------------
| Prefix  : /api/v1
| Auth    : Laravel Sanctum (token-based)
| Format  : JSON
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ─── Public Routes (tidak butuh token) ───────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:login')  // max 10 req/menit per IP
            ->name('login');
    });

    // ─── Protected Routes (butuh token Sanctum) ──────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::get('me',      [AuthController::class, 'me'])->name('me');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });

        // Keyword
        Route::prefix('keywords')->name('keywords.')->group(function () {
            Route::get('/',           [KeywordController::class, 'index'])->name('index');
            Route::post('/',          [KeywordController::class, 'store'])->name('store');
            Route::delete('/{id}',    [KeywordController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/analyze', [KeywordController::class, 'analyze'])->name('analyze');
        });

        // News
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/',      [NewsController::class, 'index'])->name('index');
            Route::get('/{id}',  [NewsController::class, 'show'])->name('show');
        });

        // Dashboard
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('stats',      [DashboardController::class, 'stats'])->name('stats');
            Route::get('chart',      [DashboardController::class, 'chart'])->name('chart');
            Route::get('top-media',  [DashboardController::class, 'topMedia'])->name('top-media');
            Route::get('top-issues', [DashboardController::class, 'topIssues'])->name('top-issues');
        });

        // Geo / Peta
        Route::prefix('geo')->name('geo.')->group(function () {
            Route::get('regions', [GeoController::class, 'regions'])->name('regions');
            Route::get('heatmap', [GeoController::class, 'heatmap'])->name('heatmap');
        });

        // Laporan
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',      [ReportController::class, 'index'])->name('index');
            Route::post('/',     [ReportController::class, 'store'])->name('store');
            Route::get('/{id}',  [ReportController::class, 'show'])->name('show');
        });

        // Notifikasi
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/',                [NotificationController::class, 'index'])->name('index');
            Route::put('/{id}/read',       [NotificationController::class, 'markRead'])->name('read');
            Route::put('/read-all',        [NotificationController::class, 'markAllRead'])->name('read-all');
        });

    }); // end auth:sanctum

}); // end v1
