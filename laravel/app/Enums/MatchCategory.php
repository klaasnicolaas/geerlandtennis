<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MatchCategory: string implements HasLabel
{
    case PRACTICE = 'practice';
    case TOURNAMENT = 'tournament';

    /**
     * Get the enum values as an array.
     */
    public static function toArray(): array
    {
        return array_column(array: MatchCategory::cases(), column_key: 'value');
    }

    /**
     * Get the label for the enum value.
     */
    public function getLabel(): string
    {
        return match ($this) {
            MatchCategory::PRACTICE => 'Practice',
            MatchCategory::TOURNAMENT => 'Tournament',
        };
    }
}
