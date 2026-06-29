<?php

namespace App\Filament\Resources\AlertLogs;

use App\Filament\Resources\AlertLogs\Pages\ListAlertLogs;
use App\Models\AlertLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class AlertLogResource extends Resource
{
    protected static ?string $model = AlertLog::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static ?string $navigationLabel = 'Alert Logs';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string { return 'Alert Center'; }
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
                Tables\Columns\TextColumn::make('alertRule.name')
                    ->label('Aturan Alert')->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Pesan')->limit(60)
                    ->tooltip(fn ($record) => $record->message),
                Tables\Columns\TextColumn::make('triggered_value')
                    ->label('Nilai Terpicu')->alignCenter(),
                Tables\Columns\IconColumn::make('is_sent')
                    ->label('Terkirim')->boolean()->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListAlertLogs::route('/'),
        ];
    }
}
