<?php
namespace App\Filament\Resources\AlertLogs\Pages;
use App\Filament\Resources\AlertLogs\AlertLogResource;
use Filament\Resources\Pages\ListRecords;
class ListAlertLogs extends ListRecords {
    protected static string $resource = AlertLogResource::class;
}
