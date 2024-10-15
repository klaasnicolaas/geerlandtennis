<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TennisSetResource\Pages;
use App\Models\TennisSet;
use App\Rules\UniqueSetNumber;
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

    protected static bool $shouldRegisterNavigation = true;

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
                Forms\Components\Section::make('Set Details')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        // Match Field
                        Forms\Components\Select::make('tennis_match_id')
                            ->relationship('tennisMatch', 'id')
                            ->required()
                            ->label('Match')
                            ->native(false)
                            ->reactive()
                            ->helperText('Select the match to link this set to.'),
                        // Set Number Field
                        Forms\Components\TextInput::make('set_number')
                            ->required()
                            ->numeric()
                            ->label('Set Number')
                            ->helperText('Enter the set number (e.g., 1, 2, 3).')
                            ->rules([
                                'required',
                                'integer',
                                'min:1',
                                fn ($get): UniqueSetNumber => new UniqueSetNumber($get('tennis_match_id')),
                            ]),
                        // Winner Team Field
                        Forms\Components\Select::make('winner_team_id')
                            ->label('Winner Team')
                            ->required()
                            ->preload()
                            ->native(false)
                            ->placeholder('Select the winning team')
                            ->options(function (callable $get): array {
                                $matchId = $get('tennis_match_id');
                                if ($matchId) {
                                    $match = \App\Models\TennisMatch::find($matchId);
                                    if ($match) {
                                        $teams = [];
                                        if ($match->teamOne) {
                                            $teams[$match->teamOne->id] = $match->teamOne->name;
                                        }
                                        if ($match->teamTwo) {
                                            $teams[$match->teamTwo->id] = $match->teamTwo->name;
                                        }
                                        return $teams;
                                    }
                                }
                                return [];
                            }),
                ]),
                // Scores Section
                Forms\Components\Section::make('Scores')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('team_one_score')
                            ->required()
                            ->numeric()
                            ->label('Team One - Score')
                            ->rules(['required', 'integer', 'min:0'])
                            ->helperText('Enter the score for Team 1 in this set.'),
                        Forms\Components\TextInput::make('team_two_score')
                            ->required()
                            ->numeric()
                            ->label('Team Two - Score')
                            ->rules(['required', 'integer', 'min:0'])
                            ->helperText('Enter the score for Team 2 in this set.'),
                        // Tie Break Option
                        Forms\Components\Toggle::make('has_tie_break')
                            ->label('Tie Break')
                            ->helperText('Enable if this set includes a tie break.')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('set_number')
                    ->label('Set Number'),
                Tables\Columns\TextColumn::make('team_one_score')
                    ->label('Team 1 - Score'),
                Tables\Columns\TextColumn::make('team_two_score')
                    ->label('Team 2 - Score'),
                Tables\Columns\IconColumn::make('has_tie_break')
                    ->label('Tie Break')
                    ->boolean(),
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
