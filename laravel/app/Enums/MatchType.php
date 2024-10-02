<?php

namespace App\Enums;

enum MatchType: string
{
    case SINGLE = 'single';
    case DOUBLE = 'double';

    public static function toArray(): array
    {
        return array_column(array: MatchType::cases(), column_key: 'value');
    }
}
