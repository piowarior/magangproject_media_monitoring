<?php

namespace App\Filament\Resources\NewsSources;

use App\Filament\Resources\NewsSources\Pages\ListNewsSources;
use App\Models\NewsSource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class NewsSourceResource extends Resource
{
    protected static ?string $model = NewsSource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;
    protected static ?string $navigationLabel = 'Media Sources';
    protected static ?int $navigationSort = 1;

    // Media ditemukan otomatis oleh crawler — bukan di-input manual
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }

    public static function getNavigationGroup(): string { return 'Crawling Center'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Media')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('domain')
                    ->label('Domain')
                    ->searchable()
                    ->placeholder('—')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('total_news')
                    ->label('Total Berita')
                    ->alignCenter()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('positive_count')
                    ->label('Positif')
                    ->alignCenter()
                    ->color('success')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) =>
                        $state . ' (' . $record->positive_pct . '%)'
                    ),

                Tables\Columns\TextColumn::make('neutral_count')
                    ->label('Netral')
                    ->alignCenter()
                    ->color('warning')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) =>
                        $state . ' (' . (
                            $record->total_news > 0
                                ? round(($state / $record->total_news) * 100, 1)
                                : 0
                        ) . '%)'
                    ),

                Tables\Columns\TextColumn::make('negative_count')
                    ->label('Negatif')
                    ->alignCenter()
                    ->color('danger')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) =>
                        $state . ' (' . $record->negative_pct . '%)'
                    ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('last_crawled_at')
                    ->label('Terakhir Ditemukan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum pernah'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Diblokir'),
            ])
            ->actions([
                Action::make('blacklist')
                    ->label('Blokir')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Blokir Media Ini?')
                    ->modalDescription('Crawler tidak akan menyimpan berita dari media ini.')
                    ->action(fn ($record) => $record->update(['is_active' => false]))
                    ->visible(fn ($record) => $record->is_active),

                Action::make('unblacklist')
                    ->label('Aktifkan')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->action(fn ($record) => $record->update(['is_active' => true]))
                    ->visible(fn ($record) => ! $record->is_active),
            ])
            ->defaultSort('total_news', 'desc')
            ->description('Daftar media yang berhasil ditemukan oleh crawler. Diisi otomatis, tidak perlu input manual.')
            ->emptyStateHeading('Belum ada data media')
            ->emptyStateDescription('Data media akan otomatis muncul setelah crawler berjalan pertama kali.');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsSources::route('/'),
        ];
    }
}
