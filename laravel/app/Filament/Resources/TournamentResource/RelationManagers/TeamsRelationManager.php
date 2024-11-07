<?php

namespace App\Filament\Resources\TournamentResource\RelationManagers;

use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    protected static ?string $recordTitleAttribute = 'name';

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('name')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Team Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.registration_date')
                    ->label('Registration Date')
                    ->date(),
                Tables\Columns\TextColumn::make('pivot.status')
                    ->label('Registration Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'info' => 'registered',
                        'danger' => 'eliminated',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'registered' => 'Registered',
                        'eliminated' => 'Eliminated',
                    ]),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'registered' => 'Registered',
                                'eliminated' => 'Eliminated',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->action(function (Team $record, array $data): void {
                        $record->pivot->update([
                            'status' => $data['status'],
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
