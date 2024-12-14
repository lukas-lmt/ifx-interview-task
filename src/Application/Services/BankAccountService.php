<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\BankAccount\Repository\BankAccountRepositoryInterface;
use App\Application\Commands\CreditAccountCommand;
use App\Application\Commands\DebitAccountCommand;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\Exceptions\BankAccountNotFoundException;
use App\Domain\Services\TransactionFeeCalculator;
use App\Domain\Specifications\CurrencyMatchSpecification;
use App\Domain\Specifications\DailyDebitLimitSpecification;
use App\Domain\Specifications\SufficientBalanceSpecification;
use App\Domain\ValueObject\Currency;

readonly final class BankAccountService
{
    public function __construct(
        private BankAccountRepositoryInterface $bankAccountRepository,
        private TransactionFeeCalculator $transactionFeeCalculator
    ) {}

    public function creditAccount(CreditAccountCommand $command): void
    {
        $bankAccount = $this->bankAccountRepository->findById($command->accountId);

        if (!$bankAccount) {
            throw new BankAccountNotFoundException($command->accountId);
        }

        $currency = new Currency($command->currency);
        $payment = new Payment($command->amount, $currency);

        $bankAccount->credit($payment, new CurrencyMatchSpecification());

        $this->bankAccountRepository->save($bankAccount);
    }

    public function debitAccount(DebitAccountCommand $command): void
    {
        $bankAccount = $this->bankAccountRepository->findById($command->accountId);

        if (!$bankAccount) {
            throw new BankAccountNotFoundException($command->accountId);
        }

        $currency = new Currency($command->currency);
        $payment = new Payment($command->amount, $currency);
        $bankAccount->debit(
            $payment,
            new SufficientBalanceSpecification($this->transactionFeeCalculator),
            new DailyDebitLimitSpecification(),
            new CurrencyMatchSpecification());

        $this->bankAccountRepository->save($bankAccount);
    }
}
