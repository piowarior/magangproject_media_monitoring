<?php

namespace App\Filament\Resources\AiModelLogs;

use App\Filament\Resources\AiModelLogs\Pages\ListAiModelLogs;
use App\Models\AiModelLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class AiModelLogResource extends Resource
{
    protected static ?string $model = AiModelLog::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;
    protected static ?string $navigationLabel = 'AI Model Logs';
    protected static ?int $navigationSort = 2;

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
                    ->label('Berita')->limit(45)->searchable(),
                Tables\Columns\TextColumn::make('model_a_score')
                    ->label('Model A (Lexicon)')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 3) : '—')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('model_b_score')
                    ->label('Model B (SVM)')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 3) : '—')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('model_c_score')
                    ->label('Model C (LSTM)')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 3) : '—')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('final_score')
                    ->label('Ensemble Score')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 3) : '—')
                    ->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('processing_time_ms')
                    ->label('Waktu (ms)')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' ms' : '—')
                    ->alignCenter()->sortable(),
                Tables\Columns\TextColumn::make('model_version')
                    ->label('Versi')->badge()->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListAiModelLogs::route('/'),
        ];
    }
}
