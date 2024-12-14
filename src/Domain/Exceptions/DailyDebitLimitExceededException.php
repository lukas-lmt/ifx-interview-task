<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

class DailyDebitLimitExceededException extends DomainException
{
    public function __construct(string $accountId)
    {
        $message = sprintf(
            "Daily debit limit exceeded for account ID: %s.",
            $accountId
        );

        parent::__construct($message);
    }
}