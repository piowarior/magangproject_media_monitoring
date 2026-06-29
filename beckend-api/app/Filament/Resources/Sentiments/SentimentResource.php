<?php

namespace App\Filament\Resources\Sentiments;

use App\Filament\Resources\Sentiments\Pages\ListSentiments;
use App\Models\Sentiment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class SentimentResource extends Resource
{
    protected static ?string $model = Sentiment::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFaceSmile;
    protected static ?string $navigationLabel = 'Sentiments';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'AI Monitoring'; }
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
                Tables\Columns\TextColumn::make('news.title')
                    ->label('Judul Berita')->limit(50)->searchable()
                    ->tooltip(fn ($record) => $record->news?->title),
                Tables\Columns\TextColumn::make('final_sentiment')->label('Sentimen Final')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'positive' => 'success',
                        'negative' => 'danger',
                        'neutral'  => 'warning',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'positive' => 'Positif',
                        'negative' => 'Negatif',
                        'neutral'  => 'Netral',
                        default    => $state,
                    }),
                Tables\Columns\TextColumn::make('confidence_score')
                    ->label('Confidence Score')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state * 100, 1) . '%' : '—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('model_version')
                    ->label('Model Version')->badge()->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dianalisis')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('final_sentiment')->label('Sentimen')
                    ->options(['positive' => 'Positif', 'neutral' => 'Netral', 'negative' => 'Negatif']),
                Tables\Filters\SelectFilter::make('model_version')->label('Model Version')
                    ->relationship('news', 'title'),
            ])
            ->actions([
                Action::make('view_news')
                    ->label('Lihat Berita')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn ($record) => $record->news?->url)
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListSentiments::route('/'),
        ];
    }
}
