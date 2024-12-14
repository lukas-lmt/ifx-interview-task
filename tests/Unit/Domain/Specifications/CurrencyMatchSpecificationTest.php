<?php

declare(strict_types=1);

namespace Tests\Domain\Specifications;

use App\Domain\Services\TransactionFeeCalculator;
use PHPUnit\Framework\TestCase;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Specifications\CurrencyMatchSpecification;

class CurrencyMatchSpecificationTest extends TestCase
{
    public function testSpecificationSatisfied(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 1000.0);
        $payment = new Payment(50.0, $currency);

        $specification = new CurrencyMatchSpecification();

        $this->assertTrue($specification->isSatisfiedBy($bankAccount, $payment));
    }

    public function testSpecificationNotSatisfied(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currencyUSD = new Currency(CurrencyEnum::USD);
        $currencyEUR = new Currency(CurrencyEnum::EUR);

        $bankAccount = new BankAccount($feeCalculator, '123', $currencyUSD, 1000.0);
        $payment = new Payment(50.0, $currencyEUR);

        $specification = new CurrencyMatchSpecification();

        $this->assertFalse($specification->isSatisfiedBy($bankAccount, $payment));
    }
}
