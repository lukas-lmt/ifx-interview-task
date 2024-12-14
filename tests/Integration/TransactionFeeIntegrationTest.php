<?php

declare(strict_types=1);

namespace Tests\Integrations;

use App\Domain\Exceptions\InsufficientBalanceException;
use App\Domain\Specifications\SufficientBalanceSpecification;
use PHPUnit\Framework\TestCase;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Services\TransactionFeeCalculator;

class TransactionFeeIntegrationTest extends TestCase
{
    private TransactionFeeCalculator $feeCalculator;

    protected function setUp(): void
    {
        $this->feeCalculator = new TransactionFeeCalculator();
    }

    public function testStandardFeeIsApplied(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($this->feeCalculator, '123', $currency, 100.0);

        $payment = new Payment(100.0, $currency);

        $specification = new SufficientBalanceSpecification($this->feeCalculator);

        $this->expectException(InsufficientBalanceException::class);

        $bankAccount->debit($payment, $specification);
    }

    public function testStandardFeeWithPositiveBalance(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($this->feeCalculator, '123', $currency, 150.0);

        $payment = new Payment(100.0, $currency);
        $specification = new SufficientBalanceSpecification($this->feeCalculator);

        $bankAccount->debit($payment, $specification);

        $expectedBalance = 150.0 - (100.0 + (100.0 * 0.005)); // Amount + Fee
        $this->assertEquals($expectedBalance, $bankAccount->getBalance());
    }
}
