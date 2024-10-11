<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TennisSetResource\Pages;
use App\Models\TennisSet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TennisSetResource extends Resource
{
    protected static ?string $model = TennisSet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Sets';

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
                Forms\Components\Select::make('tennis_match_id')
                    ->relationship('tennisMatch', 'id')
                    ->required(),
                Forms\Components\TextInput::make('set_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('team_one_score')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('team_two_score')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('tie_break')
                    ->required(),
                Forms\Components\TextInput::make('winning_team')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tennisMatch.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('set_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_one_score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_two_score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('tie_break')
                    ->boolean(),
                Tables\Columns\TextColumn::make('winning_team'),
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
            'index' => Pages\ListTennisSets::route('/'),
            'create' => Pages\CreateTennisSet::route('/create'),
            'edit' => Pages\EditTennisSet::route('/{record}/edit'),
        ];
    }
}
