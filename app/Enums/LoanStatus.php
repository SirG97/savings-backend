<?php

namespace App\Enums;

enum LoanStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case DISBURSED = 'disbursed';
    case DUE = 'due';
    case OVERDUE = 'overdue';
    case PAID = 'paid';

    public static function toArray(): array
    {
        return [
            static::PENDING->value,
            static::APPROVED->value,
            static::REJECTED->value,
            static::DISBURSED->value,
            static::DUE->value,
            static::PAID->value,
            static::OVERDUE->value,

        ];
    }
}
