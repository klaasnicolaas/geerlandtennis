<?php

namespace App\Filament\App\Resources\TournamentSignupResource\Pages;

use App\Filament\App\Resources\TournamentSignupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTournamentSignups extends ListRecords
{
    protected static string $resource = TournamentSignupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
