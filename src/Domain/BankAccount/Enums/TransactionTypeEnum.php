<?php

declare(strict_types=1);

namespace App\Domain\BankAccount\Enums;

namespace App\Domain\BankAccount\Enums;

enum TransactionTypeEnum: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}
