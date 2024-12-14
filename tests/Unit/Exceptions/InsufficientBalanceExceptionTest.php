<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Domain\Exceptions\InsufficientBalanceException;
use PHPUnit\Framework\TestCase;

class InsufficientBalanceExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $accountId = 'some-id';
        $currentBalance = 50.0;
        $requiredAmount = 100.0;

        $exception = new InsufficientBalanceException($accountId, $currentBalance, $requiredAmount);

        $this->assertEquals(
            "Insufficient balance for account ID: some-id. Current balance: 50.00, required: 100.00.",
            $exception->getMessage()
        );
    }
}
