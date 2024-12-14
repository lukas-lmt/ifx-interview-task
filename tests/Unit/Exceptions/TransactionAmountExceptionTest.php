<?php

declare(strict_types=1);

namespace Unit\Exceptions;

use App\Domain\Exceptions\TransactionAmountException;
use PHPUnit\Framework\TestCase;

class TransactionAmountExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new TransactionAmountException();

        $this->assertEquals(
            "Transaction amount must be positive.",
            $exception->getMessage()
        );
    }
}
