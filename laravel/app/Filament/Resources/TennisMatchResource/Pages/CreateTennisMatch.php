<?php

namespace App\Filament\Resources\TennisMatchResource\Pages;

use App\Enums\MatchType;
use App\Filament\Resources\TennisMatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTennisMatch extends CreateRecord
{
    protected static string $resource = TennisMatchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tennis match created';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['match_type'] === MatchType::SINGLE->value) {
            $data['team_one_player_two_id'] = null;
            $data['team_two_player_two_id'] = null;
        }

        return $data;
    }
}
