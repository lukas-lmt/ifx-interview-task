<?php
declare(strict_types=1);

namespace App\Domain\BankAccount\ValueObject;

use App\Domain\BankAccount\Enums\TransactionTypeEnum;
use App\Domain\Exceptions\TransactionAmountException;
use DateTimeImmutable;

readonly final class Transaction
{
    public function __construct(
        private TransactionTypeEnum $type,
        private float $amount,
        private float $fee = 0.0,
        private DateTimeImmutable $date = new DateTimeImmutable()
    ) {
        if ($amount <= 0) {
            throw new TransactionAmountException();
        }
    }

    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getFee(): float
    {
        return $this->fee;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}