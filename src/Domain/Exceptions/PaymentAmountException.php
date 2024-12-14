<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use InvalidArgumentException;

class PaymentAmountException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Payment amount must be positive.");
    }
}
