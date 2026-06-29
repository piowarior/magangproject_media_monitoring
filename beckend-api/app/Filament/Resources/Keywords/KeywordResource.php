<?php

namespace App\Filament\Resources\Keywords;

use App\Filament\Resources\Keywords\Pages\CreateKeyword;
use App\Filament\Resources\Keywords\Pages\EditKeyword;
use App\Filament\Resources\Keywords\Pages\ListKeywords;
use App\Models\Keyword;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class KeywordResource extends Resource
{
    protected static ?string $model = Keyword::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;
    protected static ?string $navigationLabel = 'Keywords';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'Keyword Management'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Detail Keyword')->columns(2)->schema([
                Forms\Components\TextInput::make('keyword_text')
                    ->label('Kata Kunci')->required()->maxLength(255)
                    ->placeholder('contoh: DPRD Banten')->columnSpanFull(),

                Forms\Components\TextInput::make('region_scope')
                    ->label('Cakupan Wilayah')->default('Banten')->required(),

                Forms\Components\Select::make('status')->label('Status')
                    ->options(['active' => 'Aktif', 'inactive' => 'Nonaktif'])
                    ->default('active')->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('keyword_text')
                    ->label('Kata Kunci')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('region_scope')
                    ->label('Wilayah')->badge()->color('info'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Aktif' : 'Nonaktif'),
                Tables\Columns\TextColumn::make('news_count')->label('Berita')
                    ->counts('news')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('creator.name')->label('Dibuat Oleh')
                    ->sortable()->placeholder('—'),
                Tables\Columns\TextColumn::make('updated_at')->label('Update')
                    ->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Aktif', 'inactive' => 'Nonaktif',
                ]),
            ])
            ->actions([
                Action::make('toggle')
                    ->label(fn ($record) => $record->status === 'active' ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn ($record) => $record->status === 'active' ? Heroicon::OutlinedPause : Heroicon::OutlinedPlay)
                    ->color(fn ($record) => $record->status === 'active' ? 'warning' : 'success')
                    ->action(fn ($record) => $record->update([
                        'status' => $record->status === 'active' ? 'inactive' : 'active',
                    ])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListKeywords::route('/'),
            'create' => CreateKeyword::route('/create'),
            'edit'   => EditKeyword::route('/{record}/edit'),
        ];
    }
}
