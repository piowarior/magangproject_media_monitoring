<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Users';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string { return 'User Management'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Informasi Akun')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Lengkap')->required()->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')->email()->required()
                        ->unique(ignoreRecord: true)->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label('Password')->password()->revealable()->minLength(8)
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create')
                        ->helperText('Kosongkan jika tidak ingin mengubah password'),

                    Forms\Components\Select::make('roles')
                        ->label('Role')->relationship('roles', 'name')
                        ->required()->preload()
                        ->helperText('Operator & Pimpinan hanya bisa akses Mobile App'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin'    => 'danger',
                        'Operator' => 'warning',
                        'Pimpinan' => 'info',
                        default    => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')
                    ->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')->label('Filter Role')
                    ->relationship('roles', 'name'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}
