<?php

namespace App\Filament\Resources;

use App\Enums\MatchCategory;
use App\Enums\MatchType;
use App\Filament\Resources\TennisMatchResource\Pages;
use App\Models\TennisMatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TennisMatchResource extends Resource
{
    protected static ?string $model = TennisMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Matches';

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
                Forms\Components\Section::make('General')->schema([
                    Forms\Components\Radio::make('match_type')
                        ->options(MatchType::class)
                        ->live()
                        ->inline()
                        ->inlineLabel(false)
                        ->required()
                        ->reactive(),
                    Forms\Components\Select::make('match_category')
                        ->options(MatchCategory::class)
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('match_date')
                        ->required(),
                ])->columns(3),
                Forms\Components\Section::make('Team One')
                    ->description('Select players for the first team.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('team_one_player_one_id')
                            ->relationship('teamOnePlayerOne', 'name')
                            ->label('Team One - Player 1')
                            ->native(false)
                            ->searchable()
                            ->required()

                            // Validation rule
                            ->rules(['different:team_two_player_one_id,team_two_player_two_id,team_one_player_two_id'])
                            ->validationMessages([
                                'different' => 'The player must be different from the other players.',
                            ]),
                        Forms\Components\Select::make('team_one_player_two_id')
                            ->relationship('teamOnePlayerTwo', 'name')
                            ->label('Team One - Player 2')
                            ->visible(fn (Forms\Get $get): bool => $get('match_type') === MatchType::DOUBLE->value)
                            ->searchable()
                            ->native(false)
                            ->requiredIf('match_type', MatchType::DOUBLE->value)

                            // Validation rule
                            ->rules(['different:team_one_player_one_id,team_two_player_one_id,team_two_player_two_id'])
                            ->validationMessages([
                                'different' => 'The player must be different from the other players.',
                            ]),
                    ]),
                Forms\Components\Section::make('Team Two')
                    ->description('Select players for the second team.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('team_two_player_one_id')
                            ->relationship('teamTwoPlayerOne', 'name')
                            ->label('Team Two - Player 1')
                            ->native(false)
                            ->searchable()
                            ->required()

                            // Validation rule
                            ->rules(['different:team_one_player_one_id,team_one_player_two_id,team_two_player_two_id'])
                            ->validationMessages([
                                'different' => 'The player must be different from the other players.',
                            ]),
                        Forms\Components\Select::make('team_two_player_two_id')
                            ->relationship('teamTwoPlayerTwo', 'name')
                            ->label('Team Two - Player 2')
                            ->visible(fn (Forms\Get $get): bool => $get('match_type') === MatchType::DOUBLE->value)
                            ->searchable()
                            ->native(false)
                            ->requiredIf('match_type', MatchType::DOUBLE->value)

                            // Validation rule
                            ->rules(['different:team_one_player_one_id,team_one_player_two_id,team_two_player_one_id'])
                            ->validationMessages([
                                'different' => 'The player must be different from the other players.',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('match_type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('match_category')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('team_one_player_one_id')
                    ->label('Team - One')
                    ->formatStateUsing(function ($record): mixed {
                        return $record->match_type === MatchType::DOUBLE
                            ? "{$record->teamOnePlayerOne->name} & {$record->teamOnePlayerTwo->name}"
                            : $record->teamOnePlayerOne->name;
                    }),
                Tables\Columns\TextColumn::make('team_two_player_one_id')
                    ->label('Team - Two')
                    ->formatStateUsing(function ($record): mixed {
                        return $record->match_type === MatchType::DOUBLE
                            ? "{$record->teamTwoPlayerOne->name} & {$record->teamTwoPlayerTwo->name}"
                            : $record->teamTwoPlayerOne->name;
                    }),
                Tables\Columns\TextColumn::make('match_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ListTennisMatches::route('/'),
            'create' => Pages\CreateTennisMatch::route('/create'),
            'edit' => Pages\EditTennisMatch::route('/{record}/edit'),
        ];
    }
}
