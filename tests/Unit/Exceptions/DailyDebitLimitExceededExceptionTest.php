<?php

declare(strict_types=1);

namespace Unit\Exceptions;

use App\Domain\Exceptions\DailyDebitLimitExceededException;
use PHPUnit\Framework\TestCase;

class DailyDebitLimitExceededExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new DailyDebitLimitExceededException('some-account-id');

        $this->assertEquals(
            "Daily debit limit exceeded for account ID: some-account-id.",
            $exception->getMessage()
        );
    }
}
