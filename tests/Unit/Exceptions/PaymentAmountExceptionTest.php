<?php

declare(strict_types=1);

namespace Unit\Exceptions;

use App\Domain\Exceptions\PaymentAmountException;
use PHPUnit\Framework\TestCase;

class PaymentAmountExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new PaymentAmountException();

        $this->assertEquals(
            "Payment amount must be positive.",
            $exception->getMessage()
        );
    }
}
