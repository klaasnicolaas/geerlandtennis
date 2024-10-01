<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Settings';

    /**
     * Display number of records in the navigation.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    DateTimePicker::make('email_verified_at'),
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn ($livewire) => ($livewire instanceof CreateUser))
                        ->maxLength(255),
                    TextInput::make('rating_singles')
                        ->numeric()
                        ->step('0.0001')
                        ->required()
                        ->default('9.0000'),
                    TextInput::make('rating_doubles')
                        ->numeric()
                        ->step('0.0001')
                        ->required()
                        ->default('9.0000'),
                    Select::make('roles')
                        ->label('Roles')
                        ->preload()
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->placeholder('Select the roles'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->circular()
                    ->label('Avatar')
                    ->defaultImageUrl(function ($record) {
                        $firstLetter = substr($record->name, 0, 1);
                        $avatarUrlIsNull = 'https://ui-avatars.com/api/?name='.urlencode($firstLetter).'&color=FFFFFF&background=030712';

                        return $record->avatar_url ?? $avatarUrlIsNull;
                    }),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('rating_singles')
                    ->label('Single')
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 3).' ('.intval($state).')';
                    })
                    ->sortable(),
                TextColumn::make('rating_doubles')
                    ->label('Double')
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 3).' ('.intval($state).')';
                    })
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->sortable()
                    ->badge()
                    ->separator(', '),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
