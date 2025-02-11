<?php

namespace App\Enums;

enum LoanDuration: int
{
    case ONE_MONTH = 1;
    case TWO_MONTH = 2;
    case THREE_MONTH = 3;
    case FOUR_MONTH = 4;
    case FIVE_MONTH = 5;
    case SIX_MONTH = 6;

    public static function toArray(): array
    {
        return [
            static::ONE_MONTH->value,
            static::TWO_MONTH->value,
            static::THREE_MONTH->value,
            static::FOUR_MONTH->value,
            static::FIVE_MONTH->value,
            static::SIX_MONTH->value
        ];
    }

}
