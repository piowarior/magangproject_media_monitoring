<?php
namespace App\Filament\Resources\NewsSources\Pages;
use App\Filament\Resources\NewsSources\NewsSourceResource;
use Filament\Resources\Pages\EditRecord;
class EditNewsSource extends EditRecord {
    protected static string $resource = NewsSourceResource::class;
}
