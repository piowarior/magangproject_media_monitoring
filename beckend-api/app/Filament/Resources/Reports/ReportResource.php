<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Reports\Pages\CreateReport;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Models\Report;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Buat Laporan Baru')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Laporan')
                        ->required()
                        ->placeholder('contoh: Analisis Sentimen DPRD Banten Juni 2026')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('keyword_id')
                        ->label('Keyword')
                        ->relationship('keyword', 'keyword_text')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\Hidden::make('created_by')
                        ->default(fn () => auth()->id()),

                    Forms\Components\DatePicker::make('period_start')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->native(false),

                    Forms\Components\DatePicker::make('period_end')
                        ->label('Tanggal Selesai')
                        ->required()
                        ->native(false)
                        ->after('period_start'),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft'     => 'Draft',
                            'generated' => 'Sudah Generate',
                            'exported'  => 'Sudah Export',
                        ])
                        ->default('draft'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Laporan')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),

                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('period_start')
                    ->label('Periode')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->period_start->format('d M Y') . ' – ' . $record->period_end->format('d M Y')
                    ),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray'    => 'draft',
                        'success' => 'generated',
                        'primary' => 'exported',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft'     => 'Draft',
                        'generated' => 'Sudah Generate',
                        'exported'  => 'Sudah Export',
                        default     => $state,
                    }),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('keyword')
                    ->label('Keyword')
                    ->relationship('keyword', 'keyword_text'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'generated' => 'Sudah Generate',
                        'exported'  => 'Sudah Export',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReports::route('/'),
            'create' => CreateReport::route('/create'),
            'view'   => ViewReport::route('/{record}'),
        ];
    }
}
