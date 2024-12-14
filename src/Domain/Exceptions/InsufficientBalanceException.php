<?php

namespace App\Domain\Exceptions;

use DomainException;

class InsufficientBalanceException extends DomainException
{
    public function __construct(string $accountId, $currentBalance, $requiredAmount)
    {
        $message = sprintf(
            "Insufficient balance for account ID: %s. Current balance: %.2f, required: %.2f.",
            $accountId,
            $currentBalance,
            $requiredAmount
        );
        parent::__construct($message);
    }
}