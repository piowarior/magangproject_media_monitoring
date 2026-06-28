<?php

namespace App\Filament\Resources\CrawledLogs\Pages;

use App\Filament\Resources\CrawledLogs\CrawledLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCrawledLogs extends ListRecords
{
    protected static string $resource = CrawledLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
