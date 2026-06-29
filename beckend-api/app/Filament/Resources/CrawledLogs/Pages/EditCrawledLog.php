<?php

namespace App\Filament\Resources\CrawledLogs\Pages;

use App\Filament\Resources\CrawledLogs\CrawledLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCrawledLog extends EditRecord
{
    protected static string $resource = CrawledLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
