<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK = 'bank';

    public static function toArray(): array
    {
        return [
            static::CASH->value,
            static::BANK->value,

        ];
    }
}
