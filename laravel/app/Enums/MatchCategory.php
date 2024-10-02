<?php

namespace App\Enums;

enum MatchCategory: string
{
    case PRACTICE = 'practice';
    case TOURNAMENT = 'tournament';

    public static function toArray(): array
    {
        return array_column(array: MatchCategory::cases(), column_key: 'value');
    }
}
