<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;

class CurrencyTest extends TestCase
{
    public function testCurrencyCreation(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $this->assertEquals(CurrencyEnum::USD, $currency->getCode());
    }

    public function testCurrencyEquals(): void
    {
        $currency1 = new Currency(CurrencyEnum::USD);
        $currency2 = new Currency(CurrencyEnum::USD);

        $this->assertTrue($currency1->equals($currency2));
    }

    public function testCurrencyNotEquals(): void
    {
        $currency1 = new Currency(CurrencyEnum::USD);
        $currency3 = new Currency(CurrencyEnum::EUR);

        $this->assertFalse($currency1->equals($currency3));
    }
}
