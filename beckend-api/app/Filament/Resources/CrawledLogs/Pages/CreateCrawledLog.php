<?php

namespace App\Filament\Resources\CrawledLogs\Pages;

use App\Filament\Resources\CrawledLogs\CrawledLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCrawledLog extends CreateRecord
{
    protected static string $resource = CrawledLogResource::class;
}
