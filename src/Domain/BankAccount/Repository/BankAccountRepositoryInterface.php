<?php

declare(strict_types=1);

namespace App\Domain\BankAccount\Repository;

use App\Domain\BankAccount\Entity\BankAccount;

interface BankAccountRepositoryInterface
{
    public function findById(string $id): ?BankAccount;

    public function save(BankAccount $bankAccount): void;

    public function delete(BankAccount $bankAccount): void;
}
