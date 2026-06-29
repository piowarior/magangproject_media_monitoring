<?php
namespace App\Filament\Resources\KeywordGroups\Pages;
use App\Filament\Resources\KeywordGroups\KeywordGroupResource;
use Filament\Resources\Pages\ListRecords;
class ListKeywordGroups extends ListRecords {
    protected static string $resource = KeywordGroupResource::class;
}
