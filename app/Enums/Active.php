<?php

namespace App\Enums;

enum Active: int
{
    case TRUE = 1;

    case FALSE = 0;

    public static function toArray(): array
    {
        return [
            static::TRUE->value,
            static::FALSE->value,

        ];
    }
}
