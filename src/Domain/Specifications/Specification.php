<?php

declare(strict_types=1);

namespace App\Domain\Specifications;

use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;

interface Specification
{
    public function isSatisfiedBy(BankAccount $bankAccount, Payment $payment): bool;

    public function throwIfNotSatisfiedBy(BankAccount $bankAccount, Payment $payment): void;
}
