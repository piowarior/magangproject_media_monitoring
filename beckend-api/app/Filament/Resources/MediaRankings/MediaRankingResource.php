<?php

namespace App\Filament\Resources\MediaRankings;

use App\Filament\Resources\MediaRankings\Pages\ListMediaRankings;
use App\Models\MediaRanking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class MediaRankingResource extends Resource
{
    protected static ?string $model = MediaRanking::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;
    protected static ?string $navigationLabel = 'Media Rankings';
    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('source.name')
                    ->label('Media')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('period')->label('Periode')
                    ->badge()->color('gray'),
                Tables\Columns\TextColumn::make('total_news')
                    ->label('Total Berita')->alignCenter()->sortable(),
                Tables\Columns\TextColumn::make('positive_count')
                    ->label('Positif')->alignCenter()->color('success')->sortable(),
                Tables\Columns\TextColumn::make('neutral_count')
                    ->label('Netral')->alignCenter()->color('warning')->sortable(),
                Tables\Columns\TextColumn::make('negative_count')
                    ->label('Negatif')->alignCenter()->color('danger')->sortable(),
                Tables\Columns\TextColumn::make('sentiment_score')
                    ->label('Skor')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '—')
                    ->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('rank')
                    ->label('Rank')->alignCenter()->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')->date('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('period')
                    ->options(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan']),
                Tables\Filters\SelectFilter::make('source')
                    ->relationship('source', 'name'),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaRankings::route('/'),
        ];
    }
}
