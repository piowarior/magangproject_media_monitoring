<?php

namespace App\Filament\Resources\AlertRules;

use App\Filament\Resources\AlertRules\Pages\CreateAlertRule;
use App\Filament\Resources\AlertRules\Pages\EditAlertRule;
use App\Filament\Resources\AlertRules\Pages\ListAlertRules;
use App\Models\AlertRule;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class AlertRuleResource extends Resource
{
    protected static ?string $model = AlertRule::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;
    protected static ?string $navigationLabel = 'Alert Rules';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'Alert Center'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Konfigurasi Alert')->columns(2)->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Aturan')->required()->maxLength(255)
                    ->placeholder('contoh: Alert Negatif DPRD Banten')->columnSpanFull(),

                Forms\Components\Select::make('keyword_id')
                    ->label('Keyword')->relationship('keyword', 'keyword_text')
                    ->searchable()->preload()->required(),

                Forms\Components\Select::make('condition_type')->label('Kondisi')
                    ->options([
                        'negative_pct_above' => 'Sentimen Negatif > X%',
                        'news_count_above'   => 'Jumlah Berita > X',
                        'sudden_spike'       => 'Lonjakan Berita Mendadak',
                    ])->required(),

                Forms\Components\TextInput::make('threshold_value')
                    ->label('Nilai Ambang Batas')->numeric()->required()
                    ->helperText('Contoh: 60 = jika negatif > 60%'),

                Forms\Components\Select::make('notification_channel')
                    ->label('Kirim Notif Via')
                    ->options(['in_app' => 'Dalam App', 'email' => 'Email'])
                    ->default('in_app'),

                Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Aturan')
                    ->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('keyword.keyword_text')
                    ->label('Keyword')->badge()->color('info'),
                Tables\Columns\TextColumn::make('condition_type')->label('Kondisi')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'negative_pct_above' => 'Negatif > X%',
                        'news_count_above'   => 'Berita > X',
                        'sudden_spike'       => 'Lonjakan Berita',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('threshold_value')
                    ->label('Ambang')->alignCenter(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')->boolean()->alignCenter(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListAlertRules::route('/'),
            'create' => CreateAlertRule::route('/create'),
            'edit'   => EditAlertRule::route('/{record}/edit'),
        ];
    }
}
