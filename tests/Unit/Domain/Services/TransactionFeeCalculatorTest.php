<?php

declare(strict_types=1);

namespace Tests\Domain\Services;

use PHPUnit\Framework\TestCase;
use App\Domain\Services\TransactionFeeCalculator;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;

class TransactionFeeCalculatorTest extends TestCase
{
    public function testCalculateStandardFee(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $payment = new Payment(100.0, $currency);

        $bankAccountMock = $this->createMock(BankAccount::class);

        $feeCalculator = new TransactionFeeCalculator();
        $fee = $feeCalculator->calculate($bankAccountMock, $payment);

        $this->assertEquals(0.5, $fee);
    }
}
