<?php

namespace App\Filament\Resources\TennisSetResource\Pages;

use App\Filament\Resources\TennisSetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTennisSets extends ListRecords
{
    protected static string $resource = TennisSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
