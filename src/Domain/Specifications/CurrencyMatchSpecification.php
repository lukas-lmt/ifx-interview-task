<?php

declare(strict_types=1);

namespace App\Domain\Specifications;

use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\Exceptions\CurrencyMismatchException;

final class CurrencyMatchSpecification implements Specification
{
    public function isSatisfiedBy(BankAccount $bankAccount, Payment $payment): bool
    {
        return $bankAccount->getCurrency()->equals($payment->getCurrency());
    }

    public function throwIfNotSatisfiedBy(BankAccount $bankAccount, Payment $payment): void
    {
        if (!$this->isSatisfiedBy($bankAccount, $payment)) {
            throw new CurrencyMismatchException( $bankAccount->getCurrency()->getCode()->value, $payment->getCurrency()->getCode()->value);
        }
    }
}
