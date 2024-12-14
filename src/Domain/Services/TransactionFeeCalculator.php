<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;

/**
 * Defined as domain service to possible change fee logic to more dynamic
 * i.e. $feePercentage = $account->isPremium() ? 0.0025 : self::STANDARD_FEE;
 */
class TransactionFeeCalculator
{
    const STANDARD_FEE = 0.005;

    public function calculate(BankAccount $account, Payment $payment): float
    {
        return $payment->getAmount() * self::STANDARD_FEE;
    }
}
