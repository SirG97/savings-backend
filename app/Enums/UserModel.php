<?php

namespace App\Enums;

use App\Models\Admin;
use App\Models\Auditor;
use App\Models\SuperAdmin;
use App\Models\Marketer;

enum UserModel: string
{

    case SUPER_ADMIN = SuperAdmin::class;
    case ADMIN = Admin::class;
    case AUDITOR = Auditor::class;

    case MARKETER = Marketer::class;


    public static function toArray(): array
    {
        return [
            static::SUPER_ADMIN->value,
            static::ADMIN->value,
            static::AUDITOR->value,
            static::MARKETER->value,
        ];

    }

    public static function getType($type): string{
        return match ($type) {
            'super_admin' => static::SUPER_ADMIN->value,
            'admin' => static::ADMIN->value,
            'auditor' => static::AUDITOR->value,
            default => static::MARKETER->value,
        };
    }
}
