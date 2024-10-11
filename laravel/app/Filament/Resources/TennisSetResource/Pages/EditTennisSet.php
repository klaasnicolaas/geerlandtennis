<?php

namespace App\Filament\Resources\TennisSetResource\Pages;

use App\Filament\Resources\TennisSetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTennisSet extends EditRecord
{
    protected static string $resource = TennisSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
