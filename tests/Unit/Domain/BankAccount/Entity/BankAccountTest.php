<?php

declare(strict_types=1);

namespace Tests\Domain\BankAccount\Entity;

use App\Domain\Exceptions\CurrencyMismatchException;
use App\Domain\Exceptions\DailyDebitLimitExceededException;
use App\Domain\Exceptions\InsufficientBalanceException;
use App\Domain\Services\TransactionFeeCalculator;
use PHPUnit\Framework\TestCase;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Specifications\DailyDebitLimitSpecification;
use App\Domain\Specifications\SufficientBalanceSpecification;
use App\Domain\Specifications\CurrencyMatchSpecification;

class BankAccountTest extends TestCase
{
    public function testCredit(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount(new TransactionFeeCalculator(), '123', $currency);

        $payment = new Payment(100.0, $currency);
        $bankAccount->credit($payment, new CurrencyMatchSpecification());

        $this->assertEquals(100.0, $bankAccount->getBalance());
    }

    public function testCreditThrowsExceptionForCurrencyMismatch(): void
    {
        $currencyUSD = new Currency(CurrencyEnum::USD);
        $currencyEUR = new Currency(CurrencyEnum::EUR);

        $bankAccount = new BankAccount(new TransactionFeeCalculator(), '123', $currencyUSD);
        $payment = new Payment(100.0, $currencyEUR);

        $this->expectException(CurrencyMismatchException::class);

        $bankAccount->credit($payment, new CurrencyMatchSpecification());
    }

    public function testDebit(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 200.0);

        $payment = new Payment(50.0, $currency);
        $bankAccount->debit($payment, new SufficientBalanceSpecification($feeCalculator), new DailyDebitLimitSpecification(), new CurrencyMatchSpecification());

        $this->assertEquals(149.75, $bankAccount->getBalance()); // 50 + 0.5% fee
    }

    public function testDebitThrowsExceptionForInsufficientBalance(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 100.0);

        $payment = new Payment(200.0, $currency);

        $this->expectException(InsufficientBalanceException::class);

        $bankAccount->debit($payment, new SufficientBalanceSpecification($feeCalculator));
    }

    public function testDebitThrowsExceptionForExceededDailyLimit(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 1000.0);

        $payment = new Payment(50.0, $currency);

        for ($i = 0; $i < 3; $i++) {
            $bankAccount->debit($payment, new SufficientBalanceSpecification($feeCalculator), new DailyDebitLimitSpecification(), new CurrencyMatchSpecification());
        }

        $this->expectException(DailyDebitLimitExceededException::class);

        $bankAccount->debit($payment, new SufficientBalanceSpecification($feeCalculator), new DailyDebitLimitSpecification(), new CurrencyMatchSpecification());
    }

    public function testDebitThrowsExceptionForCurrencyMismatch(): void
    {
        $feeCalculator = new TransactionFeeCalculator();
        $currencyUSD = new Currency(CurrencyEnum::USD);
        $currencyEUR = new Currency(CurrencyEnum::EUR);

        $bankAccount = new BankAccount($feeCalculator, '123', $currencyUSD, 100.0);
        $payment = new Payment(50.0, $currencyEUR);

        $this->expectException(CurrencyMismatchException::class);

        $bankAccount->debit($payment, new SufficientBalanceSpecification($feeCalculator), new DailyDebitLimitSpecification(), new CurrencyMatchSpecification());
    }

    public function testGetId(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $feeCalculator = new TransactionFeeCalculator();
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 100.0);

        $this->assertEquals('123', $bankAccount->getId());
    }

}
