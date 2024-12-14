<?php

declare(strict_types=1);

namespace Tests\Domain\Specifications;

use App\Domain\Services\TransactionFeeCalculator;
use PHPUnit\Framework\TestCase;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Specifications\SufficientBalanceSpecification;

class SufficientBalanceSpecificationTest extends TestCase
{
    public function testSpecificationSatisfied(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 200.0);
        $payment = new Payment(100.0, $currency);

        $specification = new SufficientBalanceSpecification($feeCalculator);

        $this->assertTrue($specification->isSatisfiedBy($bankAccount, $payment));
    }

    public function testSpecificationNotSatisfied(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 50.0);
        $payment = new Payment(100.0, $currency);

        $specification = new SufficientBalanceSpecification($feeCalculator);

        $this->assertFalse($specification->isSatisfiedBy($bankAccount, $payment));
    }
}
