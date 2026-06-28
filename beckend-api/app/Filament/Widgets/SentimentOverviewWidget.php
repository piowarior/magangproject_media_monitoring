<?php

namespace App\Filament\Widgets;

use App\Models\News;
use App\Models\Sentiment;
use App\Models\Keyword;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SentimentOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    // Auto-refresh setiap 60 detik
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $today       = now()->toDateString();
        $todayNews   = News::whereDate('created_at', $today)->count();
        $totalNews   = News::count();

        $positif  = Sentiment::where('final_sentiment', 'positive')->count();
        $negatif  = Sentiment::where('final_sentiment', 'negative')->count();
        $netral   = Sentiment::where('final_sentiment', 'neutral')->count();
        $total    = $positif + $negatif + $netral;

        $activeKeywords = Keyword::where('status', 'active')->count();

        return [
            Stat::make('Berita Hari Ini', $todayNews)
                ->description('Total berita: ' . number_format($totalNews))
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('info'),

            Stat::make('Sentimen Positif', $positif)
                ->description($total > 0 ? number_format(($positif / $total) * 100, 1) . '% dari total' : 'Belum ada data')
                ->descriptionIcon('heroicon-o-face-smile')
                ->color('success'),

            Stat::make('Sentimen Negatif', $negatif)
                ->description($total > 0 ? number_format(($negatif / $total) * 100, 1) . '% dari total' : 'Belum ada data')
                ->descriptionIcon('heroicon-o-face-frown')
                ->color('danger'),

            Stat::make('Keyword Aktif', $activeKeywords)
                ->description('Keyword yang sedang dipantau')
                ->descriptionIcon('heroicon-o-magnifying-glass')
                ->color('warning'),
        ];
    }
}
