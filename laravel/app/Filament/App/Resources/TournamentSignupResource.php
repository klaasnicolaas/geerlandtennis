<?php

namespace App\Filament\App\Resources;

use App\Enums\MatchType;
use App\Filament\App\Resources\TournamentSignupResource\Pages;
use App\Models\Tournament;
use Auth;
use App\Models\User;
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
                        return $record->tournament_type === MatchType::DOUBLE ? [
                            Forms\Components\Select::make('teammate')
                                ->label('Select Your Teammate')
                                ->options(Auth::user()->getAvailableTeammates($record->id)->pluck('name', 'id'))
                                ->native(false)
                                ->searchable()
                                ->required(),
                        ] : [];
                    })
                    ->action(function (Tournament $record, array $data): void {
                        $user = Auth::user();

                        if (!$user instanceof User) {
                            throw new \Exception('You must be logged in to register for a tournament.');
                        }

                        $teammateId = $data['teammate'] ?? null;
                        $record->registerTeamTournament($user, $teammateId);

                        Notification::make()
                            ->title('Tournament Sign Up')
                            ->success()
                            ->body('You have successfully signed up for the tournament!')
                            ->send();
                    })
                    ->hidden(fn($record): bool => Auth::user()->isRegisteredForTournament($record))
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
}
