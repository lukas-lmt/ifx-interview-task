<?php

declare(strict_types=1);

namespace App\Domain\BankAccount\ValueObject;

use App\Domain\Exceptions\PaymentAmountException;
use App\Domain\ValueObject\Currency;

readonly final class Payment
{
    public function __construct(
        private float $amount,
        private Currency $currency
    ) {
        if ($amount <= 0) {
            throw new PaymentAmountException();\InvalidArgumentException("Payment amount must be positive.");
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function equals(Payment $payment): bool
    {
        return $this->amount === $payment->getAmount()
            && $this->currency->equals($payment->getCurrency());
    }
}
