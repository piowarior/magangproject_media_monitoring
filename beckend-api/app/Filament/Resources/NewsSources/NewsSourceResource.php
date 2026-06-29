<?php

namespace App\Filament\Resources\NewsSources;

use App\Filament\Resources\NewsSources\Pages\ListNewsSources;
use App\Filament\Resources\NewsSources\Pages\CreateNewsSource;
use App\Filament\Resources\NewsSources\Pages\EditNewsSource;
use App\Models\NewsSource;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class NewsSourceResource extends Resource
{
    protected static ?string $model = NewsSource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRss;
    protected static ?string $navigationLabel = 'RSS Sources';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'Crawling Center'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Sumber Berita')->columns(2)->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Sumber')->required()->maxLength(255)
                    ->placeholder('contoh: Kompas'),

                Forms\Components\Select::make('source_type')->label('Tipe Sumber')
                    ->options(['rss' => 'RSS Feed', 'api' => 'API', 'scraper' => 'Web Scraper'])
                    ->default('rss')->required(),

                Forms\Components\TextInput::make('base_url')
                    ->label('URL RSS / Base URL')->required()->url()
                    ->placeholder('https://news.google.com/rss/...')->columnSpanFull(),

                Forms\Components\TextInput::make('priority')->label('Prioritas')
                    ->numeric()->default(10)->minValue(1)->maxValue(100)
                    ->helperText('1 = prioritas tertinggi, 100 = terendah'),

                Forms\Components\TextInput::make('crawl_interval_minutes')
                    ->label('Interval Crawl (menit)')->numeric()->default(60),

                Forms\Components\Toggle::make('is_active')->label('Aktif')
                    ->default(true)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Sumber')
                    ->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('source_type')->label('Tipe')->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('priority')->label('Prioritas')
                    ->alignCenter()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')
                    ->boolean()->alignCenter(),
                Tables\Columns\TextColumn::make('last_crawled_at')->label('Terakhir Crawl')
                    ->dateTime('d M Y H:i')->placeholder('Belum pernah')->sortable(),
                Tables\Columns\TextColumn::make('news_count')->label('Total Berita')
                    ->counts('news')->sortable()->alignCenter(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('priority', 'asc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListNewsSources::route('/'),
            'create' => CreateNewsSource::route('/create'),
            'edit'   => EditNewsSource::route('/{record}/edit'),
        ];
    }
}
