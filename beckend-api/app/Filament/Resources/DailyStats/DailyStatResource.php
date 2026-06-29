<?php

namespace App\Filament\Resources\DailyStats;

use App\Filament\Resources\DailyStats\Pages\ListDailyStats;
use App\Models\DailyStat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class DailyStatResource extends Resource
{
    protected static ?string $model = DailyStat::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Daily Statistics';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string { return 'Analytics'; }
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')->badge()->color('info'),
                Tables\Columns\TextColumn::make('total_news')
                    ->label('Total')->alignCenter()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('positive')
                    ->label('Positif')->alignCenter()->color('success'),
                Tables\Columns\TextColumn::make('neutral')
                    ->label('Netral')->alignCenter()->color('warning'),
                Tables\Columns\TextColumn::make('negative')
                    ->label('Negatif')->alignCenter()->color('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('keyword')
                    ->relationship('keyword', 'keyword_text'),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListDailyStats::route('/'),
        ];
    }
}
