<?php
namespace App\Filament\Resources\DailyStats\Pages;
use App\Filament\Resources\DailyStats\DailyStatResource;
use Filament\Resources\Pages\ListRecords;
class ListDailyStat extends ListRecords {
    protected static string $resource = DailyStatResource::class;
}
