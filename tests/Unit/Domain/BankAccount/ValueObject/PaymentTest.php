<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObject;

use App\Domain\Exceptions\PaymentAmountException;
use PHPUnit\Framework\TestCase;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\BankAccount\ValueObject\Payment;

class PaymentTest extends TestCase
{
    public function testPaymentCreation(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $payment = new Payment(100.0, $currency);

        $this->assertEquals(100.0, $payment->getAmount());
        $this->assertTrue($currency->equals($payment->getCurrency()));
    }

    public function testPaymentEquals(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $payment1 = new Payment(100.0, $currency);
        $payment2 = new Payment(100.0, $currency);

        $this->assertTrue($payment1->equals($payment2));
    }

    public function testPaymentNotEquals(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $payment1 = new Payment(100.0, $currency);
        $payment2 = new Payment(200.0, $currency);

        $this->assertFalse($payment1->equals($payment2));
    }

    public function testPaymentCreationThrowsExceptionForNegativeAmount(): void
    {
        $currency = new Currency(CurrencyEnum::USD);

        $this->expectException(PaymentAmountException::class);

        new Payment(-100.0, $currency);
    }
}
