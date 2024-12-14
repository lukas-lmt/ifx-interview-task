<?php

declare(strict_types=1);

namespace Tests\Domain\Specifications;

use App\Domain\Services\TransactionFeeCalculator;
use PHPUnit\Framework\TestCase;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Specifications\DailyDebitLimitSpecification;
use App\Domain\Specifications\SufficientBalanceSpecification;
use App\Domain\Specifications\CurrencyMatchSpecification;

class DailyDebitLimitSpecificationTest extends TestCase
{
    public function testSpecificationSatisfied(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 1000.0);
        $payment = new Payment(50.0, $currency);

        $specification = new DailyDebitLimitSpecification();

        $this->assertTrue($specification->isSatisfiedBy($bankAccount, $payment));
    }

    public function testSpecificationNotSatisfied(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 1000.0);
        $payment = new Payment(50.0, $currency);

        $specification = new DailyDebitLimitSpecification();

        for ($i = 0; $i < 3; $i++) {
            $bankAccount->debit($payment, new SufficientBalanceSpecification($feeCalculator), new DailyDebitLimitSpecification(), new CurrencyMatchSpecification());
        }

        $this->assertFalse($specification->isSatisfiedBy($bankAccount, $payment));
    }
}
