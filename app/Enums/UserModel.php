<?php

namespace App\Enums;

use App\Models\Admin;
use App\Models\ApiUser;
use App\Models\Customer;
use App\Models\Packager;
use App\Models\Partner;
use App\Models\SuperAdmin;
use App\Models\Updater;

enum UserModel: string
{

    case SUPER_ADMIN = SuperAdmin::class;
    case ADMIN = Admin::class;


    case MARKETER = Marketer::class;


    public static function toArray(): array
    {
        return [
            static::SUPER_ADMIN->value,
            static::ADMIN->value,
            static::MARKETER->value,
        ];

    }

    public static function getType($type): string{
        switch ($type){
            case 'super_admin' :
                return static::SUPER_ADMIN->value;
            case 'admin' :
                return static::ADMIN->value;
            case 'packager' :
                return static::PACKAGER->value;
            case 'updater' :
                return static::UPDATER->value;
            case 'partner' :
                return static::PARTNER->value;
            default:
                return static::CUSTOMER->value;
        }
    }
}
