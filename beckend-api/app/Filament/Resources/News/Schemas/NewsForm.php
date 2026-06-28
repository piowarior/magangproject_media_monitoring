<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('keyword_id')
                    ->relationship('keyword', 'id')
                    ->required(),
                Select::make('source_id')
                    ->relationship('source', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('content')
                    ->columnSpanFull(),
                Textarea::make('url')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('published_at'),
                TextInput::make('hash')
                    ->required(),
            ]);
    }
}
