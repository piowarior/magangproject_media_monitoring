<?php

namespace App\Filament\Widgets;

use App\Models\CrawledLog;
use App\Models\Keyword;
use App\Models\News;
use App\Models\Sentiment;
use App\Models\AlertLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SentimentOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $today        = now()->toDateString();
        $todayNews    = News::whereDate('created_at', $today)->count();
        $totalNews    = News::count();
        $positif      = Sentiment::where('final_sentiment', 'positive')->count();
        $negatif      = Sentiment::where('final_sentiment', 'negative')->count();
        $netral       = Sentiment::where('final_sentiment', 'neutral')->count();
        $total        = max($positif + $negatif + $netral, 1);
        $activeKw     = Keyword::where('status', 'active')->count();
        $runningCrawl = CrawledLog::whereDate('created_at', $today)->count();
        $failedToday  = CrawledLog::whereDate('created_at', $today)
                          ->where('status', 'fail')->count();

        return [
            Stat::make('Berita Hari Ini', $todayNews)
                ->description('Total keseluruhan: ' . number_format($totalNews))
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('info'),

            Stat::make('Sentimen Positif', $positif)
                ->description(number_format(($positif / $total) * 100, 1) . '% dari total')
                ->descriptionIcon('heroicon-o-face-smile')
                ->color('success'),

            Stat::make('Sentimen Negatif', $negatif)
                ->description(number_format(($negatif / $total) * 100, 1) . '% dari total')
                ->descriptionIcon('heroicon-o-face-frown')
                ->color('danger'),

            Stat::make('Crawl Hari Ini', $runningCrawl)
                ->description($failedToday > 0 ? $failedToday . ' gagal' : 'Semua sukses')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color($failedToday > 0 ? 'danger' : 'success'),

            Stat::make('Keyword Aktif', $activeKw)
                ->description('Keyword yang sedang dipantau')
                ->descriptionIcon('heroicon-o-magnifying-glass')
                ->color('warning'),

            Stat::make('Sentimen Netral', $netral)
                ->description(number_format(($netral / $total) * 100, 1) . '% dari total')
                ->descriptionIcon('heroicon-o-minus-circle')
                ->color('gray'),
        ];
    }
}
