<?php

namespace App\Filament\Resources\KeywordRuns;

use App\Filament\Resources\KeywordRuns\Pages\ListKeywordRuns;
use App\Models\KeywordRun;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class KeywordRunResource extends Resource
{
    protected static ?string $model = KeywordRun::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;
    protected static ?string $navigationLabel = 'Keyword Runs';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): string { return 'Keyword Management'; }
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
                    ->label('Keyword')->searchable()->badge()->color('info'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'completed'  => 'success',
                        'processing' => 'warning',
                        'error'      => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'completed'  => 'Selesai',
                        'processing' => 'Berjalan',
                        'error'      => 'Error',
                        default      => $state,
                    }),
                Tables\Columns\TextColumn::make('total_fetched')->label('Ditemukan')
                    ->alignCenter()->sortable(),
                Tables\Columns\TextColumn::make('total_saved')->label('Disimpan')
                    ->alignCenter()->sortable(),
                Tables\Columns\TextColumn::make('triggeredBy.name')
                    ->label('Dijalankan oleh')->placeholder('Otomatis'),
                Tables\Columns\TextColumn::make('started_at')->label('Mulai')
                    ->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('ended_at')->label('Selesai')
                    ->dateTime('d M Y H:i')->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'completed'  => 'Selesai',
                    'processing' => 'Berjalan',
                    'error'      => 'Error',
                ]),
                Tables\Filters\SelectFilter::make('keyword')
                    ->relationship('keyword', 'keyword_text'),
            ])
            ->defaultSort('started_at', 'desc')
            ->poll('30s');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => ListKeywordRuns::route('/'),
        ];
    }
}
