<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use InvalidArgumentException;

class CurrencyMismatchException extends InvalidArgumentException
{
    public function __construct(string $expected, string $provided)
    {
        parent::__construct(sprintf("Currency mismatch. Expected: %s, provided: %s.", $expected, $provided));
    }
}