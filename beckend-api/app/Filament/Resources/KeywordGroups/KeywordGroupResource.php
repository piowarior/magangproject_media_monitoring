<?php

namespace App\Filament\Resources\KeywordGroups;

use App\Filament\Resources\KeywordGroups\Pages\ListKeywordGroups;
use App\Filament\Resources\KeywordGroups\Pages\CreateKeywordGroup;
use App\Filament\Resources\KeywordGroups\Pages\EditKeywordGroup;
use App\Models\KeywordGroup;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class KeywordGroupResource extends Resource
{
    protected static ?string $model = KeywordGroup::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;
    protected static ?string $navigationLabel = 'Keyword Groups';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string { return 'Keyword Management'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Group Keyword')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Group')->required()->maxLength(255)
                    ->placeholder('contoh: Grup DPRD Banten'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')->rows(3)
                    ->placeholder('Kata kunci yang dianggap satu entitas'),
                Forms\Components\Select::make('keywords')
                    ->label('Pilih Keyword')->multiple()
                    ->relationship('keywords', 'keyword_text')
                    ->searchable()->preload()
                    ->helperText('Pilih keyword yang ingin digabung menjadi satu entitas'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Group')
                    ->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('keywords_count')->label('Keyword')
                    ->counts('keywords')->alignCenter()->badge()->color('info'),
                Tables\Columns\TextColumn::make('creator.name')->label('Dibuat Oleh')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')
                    ->dateTime('d M Y')->sortable(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListKeywordGroups::route('/'),
            'create' => CreateKeywordGroup::route('/create'),
            'edit'   => EditKeywordGroup::route('/{record}/edit'),
        ];
    }
}
