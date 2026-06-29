<?php

namespace App\Filament\Resources\CrawledLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CrawledLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('keyword_id')
                    ->relationship('keyword', 'id')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('total_fetched')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_saved')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('error_message')
                    ->columnSpanFull(),
            ]);
    }
}
