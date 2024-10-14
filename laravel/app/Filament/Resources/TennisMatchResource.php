<?php

namespace App\Filament\Resources;

use App\Enums\MatchType;
use App\Filament\Resources\TennisMatchResource\Pages;
use App\Filament\Resources\TennisMatchResource\RelationManagers;
use App\Models\TennisMatch;
use App\Rules\SinglePlayerTeam;
use App\Rules\PlayerNotInBothTeams;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TennisMatchResource extends Resource
{
    protected static ?string $model = TennisMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-play';

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
                Forms\Components\Section::make('Match Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('match_type')
                            ->options(MatchType::class)
                            ->required()
                            ->native(false)
                            ->label('Match Type')
                            ->helperText('Select whether this is a singles or doubles match.'),
                        Forms\Components\DatePicker::make('match_date')
                            ->required()
                            ->label('Match Date')
                            ->helperText('Choose the date of the match.')
                            ->rules(['date', 'after_or_equal:today']),
                        Forms\Components\Toggle::make('is_practice')
                            ->label('Is Practice')
                            ->default(false),
                    ]),
                Forms\Components\Section::make('Team and Tournament')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('team_one_id')
                            ->relationship('teamOne', 'name')
                            ->required()
                            ->native(false)
                            ->label('Team One')
                            ->rules([
                                'required',
                                'different:team_two_id',
                                function (Forms\Get $get): SinglePlayerTeam|null {
                                    return $get('match_type') === MatchType::SINGLE->value
                                        ? new SinglePlayerTeam() : null;
                                }
                            ])
                            ->helperText('Select the first team.'),
                        Forms\Components\Select::make('team_two_id')
                            ->relationship('teamTwo', 'name')
                            ->required()
                            ->native(false)
                            ->label('Team Two')
                            ->rules([
                                'required',
                                'different:team_one_id',
                                function (Forms\Get $get): SinglePlayerTeam|null {
                                    // Apply the SinglePlayerTeam rule if match_type is 'single'
                                    return $get('match_type') === MatchType::SINGLE->value
                                        ? new SinglePlayerTeam() : null;
                                },
                                function (Forms\Get $get): PlayerNotInBothTeams {
                                    // Apply the PlayerNotInBothTeams rule to ensure no player is in both teams
                                    return new PlayerNotInBothTeams($get('team_one_id'), $get('team_two_id'));
                                },
                            ])
                            ->helperText('Select the second team.'),
                        Forms\Components\Select::make('tournament_id')
                            ->relationship('tournament', 'name')
                            ->nullable()
                            ->native(false)
                            ->label('Tournament'),
                        Forms\Components\Select::make('winner_team_id')
                            ->label('Winner Team')
                            ->nullable()
                            ->helperText('Select the winning team after the match is complete.')
                            ->native(false)
                            ->options(function (Forms\Get $get): array {
                                // Generate array of options based on the selected teams
                                $teamOne = $get('team_one_id');
                                $teamTwo = $get('team_two_id');

                                $options = [];
                                if ($teamOne) {
                                    $options[$teamOne] = \App\Models\Team::find($teamOne)->name;
                                }
                                if ($teamTwo) {
                                    $options[$teamTwo] = \App\Models\Team::find($teamTwo)->name;
                                }
                                return $options;
                            })
                            ->visible(fn($livewire) => $livewire instanceof Pages\EditTennisMatch)
                            ->rules(['nullable', 'exists:teams,id']),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('match_type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('teamOne.name'),
                Tables\Columns\TextColumn::make('teamTwo.name'),
                Tables\Columns\TextColumn::make('winnerTeam.name')
                    ->label('🏆 Winner'),
                Tables\Columns\TextColumn::make('match_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tournament.name'),
                Tables\Columns\IconColumn::make('is_practice')
                    ->label('Practice Match')
                    ->boolean()
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
            RelationManagers\SetsRelationManager::class,
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
