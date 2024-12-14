<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Persistence;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Persistence\InMemoryBankAccountRepository;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Services\TransactionFeeCalculator;

class InMemoryBankAccountRepositoryTest extends TestCase
{
    private InMemoryBankAccountRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryBankAccountRepository();
    }

    public function testSaveAndFindById(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $feeCalculator = new TransactionFeeCalculator();
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 100.0);

        $this->repository->save($bankAccount);

        $retrievedAccount = $this->repository->findById('123');

        $this->assertNotNull($retrievedAccount);
        $this->assertEquals($bankAccount, $retrievedAccount);
    }

    public function testFindByIdReturnsNullForNonExistentAccount(): void
    {
        $retrievedAccount = $this->repository->findById('non-existent');

        $this->assertNull($retrievedAccount);
    }

    public function testDelete(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $feeCalculator = new TransactionFeeCalculator();
        $bankAccount = new BankAccount($feeCalculator, '123', $currency, 100.0);

        $this->repository->save($bankAccount);
        $this->repository->delete($bankAccount);

        $retrievedAccount = $this->repository->findById('123');

        $this->assertNull($retrievedAccount);
    }

    public function testFindAll(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $feeCalculator = new TransactionFeeCalculator();
        $bankAccount1 = new BankAccount($feeCalculator, '123', $currency, 100.0);
        $bankAccount2 = new BankAccount($feeCalculator, '456', $currency, 200.0);

        $this->repository->save($bankAccount1);
        $this->repository->save($bankAccount2);

        $allAccounts = $this->repository->findAll();

        $this->assertCount(2, $allAccounts);
        $this->assertContains($bankAccount1, $allAccounts);
        $this->assertContains($bankAccount2, $allAccounts);
    }
}
