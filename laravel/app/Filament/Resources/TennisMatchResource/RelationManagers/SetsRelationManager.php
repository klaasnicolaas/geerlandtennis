<?php

namespace App\Filament\Resources\TennisMatchResource\RelationManagers;

use App\Rules\UniqueSetNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SetsRelationManager extends RelationManager
{
    protected static string $relationship = 'sets';
    protected static ?string $recordTitleAttribute = 'set_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                        fn ($livewire): UniqueSetNumber => new UniqueSetNumber($livewire->ownerRecord->id),
                    ]),
                // Scores Section
                Forms\Components\Fieldset::make('Scores')
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
                    ]),
                // Tie Break Option
                Forms\Components\Toggle::make('has_tie_break')
                    ->label('Tie Break')
                    ->helperText('Enable if this set includes a tie break.')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn ($record): string => "Delete Set #{$record->set_number}"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
