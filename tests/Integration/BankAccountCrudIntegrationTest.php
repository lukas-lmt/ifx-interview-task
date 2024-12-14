<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Domain\BankAccount\ValueObject\Payment;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Persistence\InMemoryBankAccountRepository;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Services\TransactionFeeCalculator;

class BankAccountCrudIntegrationTest extends TestCase
{
    private InMemoryBankAccountRepository $repository;
    private TransactionFeeCalculator $feeCalculator;

    protected function setUp(): void
    {
        $this->repository = new InMemoryBankAccountRepository();
        $this->feeCalculator = new TransactionFeeCalculator();
    }

    public function testCreateAndRetrieveBankAccount(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($this->feeCalculator, '123', $currency, 100.0);

        $this->repository->save($bankAccount);
        $retrievedAccount = $this->repository->findById('123');

        $this->assertNotNull($retrievedAccount);
        $this->assertEquals('123', $retrievedAccount->getId());
        $this->assertEquals(100.0, $retrievedAccount->getBalance());
    }

    public function testUpdateBankAccountBalance(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($this->feeCalculator, '123', $currency, 100.0);

        $this->repository->save($bankAccount);

        $bankAccount->credit(new Payment(50.0, $currency));
        $this->repository->save($bankAccount);

        $retrievedAccount = $this->repository->findById('123');

        $this->assertEquals(150.0, $retrievedAccount->getBalance());
    }

    public function testDeleteBankAccount(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount = new BankAccount($this->feeCalculator, '123', $currency, 100.0);

        $this->repository->save($bankAccount);
        $this->repository->delete($bankAccount);

        $retrievedAccount = $this->repository->findById('123');

        $this->assertNull($retrievedAccount);
    }

    public function testFindAllBankAccounts(): void
    {
        $currency = new Currency(CurrencyEnum::USD);
        $bankAccount1 = new BankAccount($this->feeCalculator, '123', $currency, 100.0);
        $bankAccount2 = new BankAccount($this->feeCalculator, '456', $currency, 200.0);

        $this->repository->save($bankAccount1);
        $this->repository->save($bankAccount2);

        $allAccounts = $this->repository->findAll();

        $this->assertCount(2, $allAccounts);
        $this->assertEquals('123', $allAccounts[0]->getId());
        $this->assertEquals('456', $allAccounts[1]->getId());
    }
}
