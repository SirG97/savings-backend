<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';

    case FEMALE = 'female';

    public static function toArray(): array
    {
        return [
            static::MALE->value,
            static::FEMALE->value,

        ];
    }
}
