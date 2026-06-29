<?php
namespace App\Filament\Resources\NewsSources\Pages;
use App\Filament\Resources\NewsSources\NewsSourceResource;
use Filament\Resources\Pages\ListRecords;
class ListNewsSource extends ListRecords {
    protected static string $resource = NewsSourceResource::class;
}
