<?php

namespace App\Filament\Resources\TennisMatchResource\Pages;

use App\Enums\MatchType;
use App\Filament\Resources\TennisMatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTennisMatch extends EditRecord
{
    protected static string $resource = TennisMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Tennis match updated';
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['match_type'] === MatchType::SINGLE->value) {
            $data['team_one_player_two_id'] = null;
            $data['team_two_player_two_id'] = null;
        }
        $record->update($data);

        return $record;
    }
}
