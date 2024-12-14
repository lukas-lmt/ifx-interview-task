<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Domain\Exceptions\BankAccountNotFoundException;
use PHPUnit\Framework\TestCase;

class BankAccountNotFoundExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new BankAccountNotFoundException('some-account-id');

        $this->assertEquals(
            "Bank account ID: some-account-id not found",
            $exception->getMessage()
        );
    }
}
