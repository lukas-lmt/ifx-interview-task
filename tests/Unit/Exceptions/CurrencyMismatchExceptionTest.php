<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Application\Enums\CurrencyEnum;
use App\Domain\Exceptions\CurrencyMismatchException;
use PHPUnit\Framework\TestCase;

class CurrencyMismatchExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new CurrencyMismatchException(CurrencyEnum::EUR->value, CurrencyEnum::PLN->value);

        $this->assertEquals(
            "Currency mismatch. Expected: EUR, provided: PLN.",
            $exception->getMessage()
        );
    }
}
