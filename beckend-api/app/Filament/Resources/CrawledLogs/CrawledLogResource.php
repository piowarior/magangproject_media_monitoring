<?php

namespace App\Filament\Resources\CrawledLogs;

use App\Filament\Resources\CrawledLogs\Pages\ListCrawledLogs;
use App\Models\CrawledLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class CrawledLogResource extends Resource
{
    protected static ?string $model = CrawledLog::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedServerStack;
    protected static ?string $navigationLabel = 'Log Crawling';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Log Crawling';

    // Read-only — data masuk dari proses crawling, bukan manual
    public static function canCreate(): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'success',
                        'danger'  => 'fail',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'success' ? 'Sukses' : 'Gagal'),

                Tables\Columns\TextColumn::make('total_fetched')
                    ->label('Diambil')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_saved')
                    ->label('Disimpan')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('error_message')
                    ->label('Pesan Error')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->error_message)
                    ->color('danger')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Sukses',
                        'fail'    => 'Gagal',
                    ]),

                Tables\Filters\SelectFilter::make('keyword')
                    ->label('Keyword')
                    ->relationship('keyword', 'keyword_text'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // auto-refresh setiap 30 detik
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCrawledLogs::route('/'),
        ];
    }
}
