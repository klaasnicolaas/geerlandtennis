<?php

namespace App\Filament\App\Resources;

use App\Enums\MatchType;
use App\Filament\App\Resources\TournamentSignupResource\Pages;
use App\Models\Team;
use App\Models\Tournament;
use Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class TournamentSignupResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $navigationLabel = 'Tournaments';

    protected static ?string $slug = 'tournaments';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tournament Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date'),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date'),
                Tables\Columns\TextColumn::make('tournament_type')
                    ->label('Tournament Type'),
                // Tables\Columns\BadgeColumn::make('team_tournament_status')
                //     ->label('Registration Status')
                //     ->getStateUsing(fn(Tournament $record): ?string => $record->getUserRegistrationStatus())
                //     ->colors([
                //         'success' => 'registered',
                //         'warning' => 'pending',
                //     ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('sign_up')
                    ->label('Sign Up')
                    ->icon('heroicon-o-paper-airplane')
                    ->button()
                    ->color('gray')
                    ->modalHeading('Sign Up for Tournament')
                    ->modalDescription('Complete the process by selecting your teammate for this doubles tournament.')
                    ->form(function (Tournament $record): array {
                        if ($record->tournament_type === MatchType::DOUBLE) {
                            return [
                                Forms\Components\Select::make('teammate')
                                    ->label('Select Your Teammate')
                                    ->options(Auth::user()->getAvailableTeammates()->pluck('name', 'id'))
                                    ->native(false)
                                    ->searchable()
                                    ->required(),
                            ];
                        }

                        return [];
                    })
                    ->action(function (Tournament $record, array $data): void {
                        if ($record->tournament_type === MatchType::SINGLE) {
                            static::signUpForSingles(tournament: $record);
                        } elseif ($record->tournament_type === MatchType::DOUBLE) {
                            static::signUpForDoubles(tournament: $record, teammateId: $data['teammate']);
                        }
                    })
                    ->hidden(function ($record): bool {
                        // Hide the button if the user is already registered for the tournament
                        return Auth::user()->isRegisteredForTournament($record);
                    })
                    ->modalWidth(MaxWidth::Large),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ]);
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
            'index' => Pages\ListTournamentSignups::route('/'),
            // 'create' => Pages\CreateTournamentSignup::route('/create'),
            // 'edit' => Pages\EditTournamentSignup::route('/{record}/edit'),
        ];
    }

    /**
     * Method to sign up for a singles tournament.
     */
    public static function signUpForSingles(Tournament $tournament): void
    {
        $user = Auth::user();

        // Create a single-player team
        $team = Team::create([
            'name' => $user->getAttribute('name').' (Singles)',
        ]);
        $team->users()->attach($user->getAuthIdentifier());

        // Register the team for the tournament
        $tournament->teams()->attach($team->id, [
            'registration_date' => now(),
            'status' => 'registered', // TODO: Use an enum for this
        ]);

        Notification::make()
            ->title('Tournament Sign Up')
            ->success()
            ->body('You have successfully signed up for the tournament!')
            ->send();
    }

    /**
     * Method to sign up for a doubles tournament.
     */
    public static function signUpForDoubles(Tournament $tournament, int $teammateId): void
    {
        $user = Auth::user();
        $teammate = $user->getAvailableTeammates()->find($teammateId);

        // Automatically generate a team name based on the user and their teammate
        $team = Team::create([
            'name' => $user->getAttribute('name').' & '.$teammate->getAttribute('name'),
        ]);
        $team->users()->attach([$user->getAuthIdentifier(), $teammate->getAttribute('id')]);

        // Register the team for the tournament
        $tournament->teams()->attach($team->id, [
            'registration_date' => now(),
            'status' => 'registered',
        ]);

        Notification::make()
            ->title('Tournament Sign Up')
            ->success()
            ->body('You have successfully signed up for the tournament!')
            ->send();
    }
}
