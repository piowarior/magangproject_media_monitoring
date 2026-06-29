<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Reports\Pages\CreateReport;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Models\Report;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;
    protected static ?string $navigationLabel = 'Reports';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'Reporting'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Buat Laporan')->columns(2)->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Laporan')->required()->columnSpanFull()
                    ->placeholder('contoh: Laporan Sentimen DPRD Banten Juni 2026'),

                Forms\Components\Select::make('keyword_id')
                    ->label('Keyword')->relationship('keyword', 'keyword_text')
                    ->required()->searchable()->preload(),

                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => auth()->id()),

                Forms\Components\DatePicker::make('period_start')
                    ->label('Tanggal Mulai')->required()->native(false),

                Forms\Components\DatePicker::make('period_end')
                    ->label('Tanggal Selesai')->required()->native(false)
                    ->after('period_start'),

                Forms\Components\Select::make('status')->label('Status')
                    ->options([
                        'draft'     => 'Draft',
                        'generated' => 'Sudah Generate',
                        'exported'  => 'Sudah Export',
                    ])->default('draft'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')->searchable()->weight('bold')->limit(45),
                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')->badge()->color('info'),
                Tables\Columns\TextColumn::make('period_start')
                    ->label('Periode')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->period_start->format('d M Y') . ' – ' . $record->period_end->format('d M Y')
                    ),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'draft'     => 'gray',
                        'generated' => 'success',
                        'exported'  => 'primary',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft'     => 'Draft',
                        'generated' => 'Generate',
                        'exported'  => 'Export',
                        default     => $state,
                    }),
                Tables\Columns\TextColumn::make('creator.name')->label('Oleh')->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListReports::route('/'),
            'create' => CreateReport::route('/create'),
        ];
    }
}
