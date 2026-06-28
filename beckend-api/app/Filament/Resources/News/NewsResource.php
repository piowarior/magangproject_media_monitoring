<?php

namespace App\Filament\Resources\News;

use App\Filament\Resources\News\Pages\ListNews;
use App\Models\News;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;
    protected static ?string $navigationLabel = 'Berita';
    protected static ?int $navigationSort = 2;

    // Read-only — berita masuk dari crawling, bukan input manual
    public static function canCreate(): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->title),

                Tables\Columns\TextColumn::make('source.name')
                    ->label('Sumber')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('sentiment.final_sentiment')
                    ->label('Sentimen')
                    ->colors([
                        'success' => 'positive',
                        'danger'  => 'negative',
                        'warning' => 'neutral',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'positive' => 'Positif',
                        'negative' => 'Negatif',
                        'neutral'  => 'Netral',
                        default    => 'Pending',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('keyword')
                    ->label('Keyword')
                    ->relationship('keyword', 'keyword_text'),

                Tables\Filters\SelectFilter::make('source')
                    ->label('Sumber Berita')
                    ->relationship('source', 'name'),
            ])
            ->actions([
                Action::make('visit')
                    ->label('Buka URL')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNews::route('/'),
        ];
    }
}
