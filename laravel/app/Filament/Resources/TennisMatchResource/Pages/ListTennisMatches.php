<?php

namespace App\Filament\Resources\TennisMatchResource\Pages;

use App\Filament\Resources\TennisMatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTennisMatches extends ListRecords
{
    protected static string $resource = TennisMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
