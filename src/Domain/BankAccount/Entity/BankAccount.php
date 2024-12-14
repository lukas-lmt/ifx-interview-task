<?php

declare(strict_types=1);

namespace App\Domain\BankAccount\Entity;

use App\Domain\BankAccount\Enums\TransactionTypeEnum;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\BankAccount\ValueObject\Transaction;
use App\Domain\Services\TransactionFeeCalculator;
use App\Domain\Specifications\Specification;
use App\Domain\ValueObject\Currency;

class BankAccount
{
    private float $balance;
    private array $transactions = [];
    private int $dailyDebitCount = 0;

    public function __construct(private readonly TransactionFeeCalculator $feeCalculator, private readonly string $id, private readonly Currency $currency, float $initialBalance = 0.0)
    {
        $this->balance = $initialBalance;
    }

    public function credit(Payment $payment, Specification ...$specifications): void
    {
        foreach ($specifications as $specification) {
            $specification->throwIfNotSatisfiedBy($this, $payment);
        }

        $this->balance += $payment->getAmount();
        $this->addTransaction(type: TransactionTypeEnum::CREDIT, amount: $payment->getAmount());
    }

    public function debit(Payment $payment, Specification ...$specifications): void
    {
        foreach ($specifications as $specification) {
            $specification->throwIfNotSatisfiedBy($this, $payment);
        }

        $transactionFee = $this->feeCalculator->calculate($this, $payment);
        $totalAmount = $payment->getAmount() + $transactionFee;

        $this->balance -= $totalAmount;
        $this->dailyDebitCount++;
        $this->addTransaction(TransactionTypeEnum::DEBIT, $payment->getAmount(), $transactionFee);
    }

    public function getLastTransactions(int $limit): array
    {
        // Assumes $this->transactions is an array of Transaction objects, sorted by date in descending order
        return array_slice($this->transactions, 0, $limit);
    }

    private function addTransaction(TransactionTypeEnum $type, float $amount, float $fee = 0.0): void
    {
        $this->transactions[] = new Transaction($type, $amount, $fee);
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
