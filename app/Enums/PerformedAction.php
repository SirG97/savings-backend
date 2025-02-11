<?php

namespace App\Enums;

enum PerformedAction: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';


    public static function toArray(): array
    {
        return [
            static::APPROVED->value,
            static::REJECTED->value,

        ];
    }

}
