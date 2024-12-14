<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

class BankAccountNotFoundException extends DomainException
{
    public function __construct(string $accountId)
    {
        $message = sprintf(
            "Bank account ID: %s not found",
            $accountId,
        );
        parent::__construct($message);
    }
}