<?php

namespace App\Filament\Resources\TennisMatchResource\RelationManagers;

use App\Filament\Resources\TennisSetResource;
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
        return TennisSetResource::form($form, self::getOwnerRecord()->id);
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
