<?php
namespace App\Filament\Resources\MediaRankings\Pages;
use App\Filament\Resources\MediaRankings\MediaRankingResource;
use Filament\Resources\Pages\ListRecords;
class ListMediaRankings extends ListRecords {
    protected static string $resource = MediaRankingResource::class;
}
