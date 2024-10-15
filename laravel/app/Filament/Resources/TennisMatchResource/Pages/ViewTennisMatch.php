<?php

namespace App\Filament\Resources\TennisMatchResource\Pages;

use App\Filament\Resources\TennisMatchResource;
use Filament\Actions;

use Filament\Resources\Pages\ViewRecord;

class ViewTennisMatch extends ViewRecord
{
    protected static string $resource = TennisMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->url(TennisMatchResource::getUrl())
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('info'),
            Actions\EditAction::make(),
        ];
    }
}
