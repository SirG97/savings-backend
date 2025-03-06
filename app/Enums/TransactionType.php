<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case TRANSFER = 'transfer';
    case LOAN_CREDIT = 'loan_credit';
    case LOAN_DEBIT = 'loan_debit';
    case EXPENSES = 'expenses';
    case COMMISSION = 'commission';
    case REVERSAL = 'reversal';

    public static function toArray(): array
    {
        return [
            static::DEPOSIT->value,
            static::WITHDRAWAL->value,
            static::TRANSFER->value,
            static::EXPENSES->value,
            static::COMMISSION->value,
            static::REVERSAL->value,

        ];
    }
}
