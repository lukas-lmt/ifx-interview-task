<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use InvalidArgumentException;

class TransactionAmountException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Transaction amount must be positive.");
    }
}
