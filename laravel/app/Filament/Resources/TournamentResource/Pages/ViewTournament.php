<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use App\Filament\Resources\TournamentResource\RelationManagers;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewTournament extends ViewRecord
{
    protected static string $resource = TournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TeamsRelationManager::class,
        ];
    }
}
