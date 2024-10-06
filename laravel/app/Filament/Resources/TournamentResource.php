<?php

namespace App\Filament\Resources;

use App\Enums\MatchType;
use App\Filament\Resources\TournamentResource\Pages;
use App\Models\Tournament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Tournaments';

    protected static ?string $navigationGroup = 'Tennis';

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
                Forms\Components\TextInput::make('name')
                    ->label('Name of the tournament')
                    ->required()
                    ->unique(ignorable: fn ($record): mixed => $record),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->nullable(),
                Forms\Components\Select::make('tournament_type')
                    ->label('Type')
                    ->options(MatchType::class)
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Start date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('End date'),
                Forms\Components\Select::make('teams')
                    ->label('Teams participating')
                    ->native(false)
                    ->multiple()
                    ->relationship('teams', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tournament_type')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teams_count')
                    ->label('Number of teams')
                    ->counts('teams'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListTournaments::route('/'),
            'create' => Pages\CreateTournament::route('/create'),
            'edit' => Pages\EditTournament::route('/{record}/edit'),
        ];
    }
}
