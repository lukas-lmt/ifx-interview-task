<?php

declare(strict_types=1);

namespace App\Domain\Specifications;

use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\Exceptions\InsufficientBalanceException;
use App\Domain\Services\TransactionFeeCalculator;

final class SufficientBalanceSpecification implements Specification
{
    public function __construct(private TransactionFeeCalculator $feeCalculator) {}
    public function isSatisfiedBy(BankAccount $bankAccount, Payment $payment): bool
    {
        $transactionFee = $this->feeCalculator->calculate($bankAccount, $payment);
        $totalDebitAmount = $payment->getAmount() + $transactionFee;

        return $bankAccount->getBalance() >= $totalDebitAmount;
    }

    public function throwIfNotSatisfiedBy(BankAccount $bankAccount, Payment $payment): void
    {
        if (!$this->isSatisfiedBy($bankAccount, $payment)) {
            throw new InsufficientBalanceException($bankAccount->getId(), $bankAccount->getBalance(), $payment->getAmount());
        }
    }
}
