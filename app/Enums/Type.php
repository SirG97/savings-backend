<?php

namespace App\Enums;

enum Type: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';

    public static function toArray(): array
    {
        return [
            static::CREDIT->value,
            static::DEBIT->value,

        ];
    }
}
