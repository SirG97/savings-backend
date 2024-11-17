<?php

namespace App\Enums;

enum UserModelType: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MARKETER = 'updater';


    public static function toArray(): array
    {
        return [
            static::SUPER_ADMIN->value,
            static::ADMIN->value,
            static::MARKETER->value,
        ];

    }
}
