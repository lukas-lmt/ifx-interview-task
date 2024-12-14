<?php

declare(strict_types=1);

namespace App\Domain\Specifications;

use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\Exceptions\DailyDebitLimitExceededException;

final class DailyDebitLimitSpecification implements Specification
{
    private const DAILY_DEBIT_LIMIT = 3;

    public function isSatisfiedBy(BankAccount $bankAccount, Payment $payment): bool
    {
        $currentDate = new \DateTimeImmutable('now');
        $lastTransactions = $bankAccount->getLastTransactions(self::DAILY_DEBIT_LIMIT);

        $todayTransactionCount = 0;

        foreach ($lastTransactions as $transaction) {
            if ($transaction->getDate()->format('Y-m-d') === $currentDate->format('Y-m-d')) {
                $todayTransactionCount++;
            }
        }

        return $todayTransactionCount < self::DAILY_DEBIT_LIMIT;
    }

    public function throwIfNotSatisfiedBy(BankAccount $bankAccount, Payment $payment): void
    {
        if (!$this->isSatisfiedBy($bankAccount, $payment)) {
            throw new DailyDebitLimitExceededException($bankAccount->getId());
        }
    }
}
