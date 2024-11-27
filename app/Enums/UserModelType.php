<?php

namespace App\Enums;

enum UserModelType: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case AUDITOR = 'auditor';
    case MARKETER = 'marketer';


    public static function toArray(): array
    {
        return [
            static::SUPER_ADMIN->value,
            static::ADMIN->value,
            static::AUDITOR->value,
            static::MARKETER->value,
        ];

    }
}
