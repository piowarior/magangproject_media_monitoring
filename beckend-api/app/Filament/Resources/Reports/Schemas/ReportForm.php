<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('keyword_id')
                    ->relationship('keyword', 'id')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                DatePicker::make('period_start')
                    ->required(),
                DatePicker::make('period_end')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
            ]);
    }
}
