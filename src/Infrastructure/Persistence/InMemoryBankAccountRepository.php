<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\Repository\BankAccountRepositoryInterface;

class InMemoryBankAccountRepository implements BankAccountRepositoryInterface
{
    private array $bankAccounts = [];

    public function findById(string $id): ?BankAccount
    {
        return $this->bankAccounts[$id] ?? null;
    }

    public function save(BankAccount $bankAccount): void
    {
        $this->bankAccounts[$bankAccount->getId()] = $bankAccount;
    }

    public function delete(BankAccount $bankAccount): void
    {
        unset($this->bankAccounts[$bankAccount->getId()]);
    }

    public function findAll(): array
    {
        return array_values($this->bankAccounts);
    }
}
