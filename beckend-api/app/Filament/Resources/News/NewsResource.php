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
    protected static ?string $navigationLabel = 'News';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'News Center'; }
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
                    ->label('Judul Berita')->searchable()->limit(55)
                    ->tooltip(fn ($record) => $record->title)->weight('medium'),

                Tables\Columns\TextColumn::make('source.name')
                    ->label('Sumber')->badge()->sortable(),

                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')->badge()->color('info'),

                Tables\Columns\TextColumn::make('sentiment.final_sentiment')
                    ->label('Sentimen')->badge()
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
                        default    => 'Pending',
                    }),

                Tables\Columns\IconColumn::make('is_duplicate')
                    ->label('Duplikat')->boolean()->alignCenter()
                    ->falseIcon(null),

                Tables\Columns\IconColumn::make('is_relevant')
                    ->label('Relevan')->boolean()->alignCenter(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Terbit')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('keyword')
                    ->label('Keyword')->relationship('keyword', 'keyword_text'),
                Tables\Filters\SelectFilter::make('source')
                    ->label('Sumber')->relationship('source', 'name'),
                Tables\Filters\SelectFilter::make('sentiment')
                    ->label('Sentimen')->options([
                        'positive' => 'Positif',
                        'neutral'  => 'Netral',
                        'negative' => 'Negatif',
                    ]),
                Tables\Filters\TernaryFilter::make('is_duplicate')
                    ->label('Duplikat'),
                Tables\Filters\TernaryFilter::make('is_relevant')
                    ->label('Relevan'),
            ])
            ->actions([
                Action::make('visit')
                    ->label('Buka URL')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),
                Action::make('mark_irrelevant')
                    ->label('Tandai Tidak Relevan')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_relevant' => false]))
                    ->visible(fn ($record) => $record->is_relevant),
                Action::make('mark_relevant')
                    ->label('Tandai Relevan')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->action(fn ($record) => $record->update(['is_relevant' => true]))
                    ->visible(fn ($record) => ! $record->is_relevant),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListNews::route('/'),
        ];
    }
}
