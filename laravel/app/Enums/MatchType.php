<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MatchType: string implements HasLabel
{
    case SINGLE = 'single';
    case DOUBLE = 'double';

    /**
     * Get the enum values as an array.
     */
    public static function toArray(): array
    {
        return array_column(array: MatchType::cases(), column_key: 'value');
    }

    /**
     * Get the label for the enum value.
     */
    public function getLabel(): string
    {
        return match ($this) {
            MatchType::SINGLE => 'Single',
            MatchType::DOUBLE => 'Double',
        };
    }
}
