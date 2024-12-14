<?php

declare(strict_types=1);

namespace Tests\Integrations;

use App\Domain\Exceptions\InsufficientBalanceException;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Persistence\InMemoryBankAccountRepository;
use App\Application\Services\BankAccountService;
use App\Application\Commands\CreditAccountCommand;
use App\Application\Commands\DebitAccountCommand;
use App\Domain\ValueObject\Currency;
use App\Application\Enums\CurrencyEnum;
use App\Domain\Services\TransactionFeeCalculator;

class BusinessRulesIntegrationTest extends TestCase
{
    private BankAccountService $bankAccountService;
    private InMemoryBankAccountRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryBankAccountRepository();
        $feeCalculator = new TransactionFeeCalculator();
        $this->bankAccountService = new BankAccountService($this->repository, $feeCalculator);

        // Create a test account
        $currency = new Currency(CurrencyEnum::USD);
        $this->repository->save(new \App\Domain\BankAccount\Entity\BankAccount(
            new TransactionFeeCalculator(), '123', $currency, 1000.0
        ));
    }

    public function testDebitFailsDueToDailyLimit(): void
    {
        $command = new DebitAccountCommand('123', 50.0, CurrencyEnum::USD);

        for ($i = 0; $i < 3; $i++) {
            $this->bankAccountService->debitAccount($command);
        }

        $this->expectException(\App\Domain\Exceptions\DailyDebitLimitExceededException::class);

        $this->bankAccountService->debitAccount($command);
    }

    public function testDebitFailsDueToInsufficientBalance(): void
    {
        $command = new DebitAccountCommand('123', 2000.0, CurrencyEnum::USD);

        $this->expectException(InsufficientBalanceException::class);

        $this->bankAccountService->debitAccount($command);
    }

    public function testDebitFailsDueToCurrencyMismatch(): void
    {
        $command = new DebitAccountCommand('123', 50.0, CurrencyEnum::EUR);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Currency mismatch.");

        $this->bankAccountService->debitAccount($command);
    }

    public function testSuccessfulCredit(): void
    {
        $command = new CreditAccountCommand('123', 50.0, CurrencyEnum::USD);

        $this->bankAccountService->creditAccount($command);

        $account = $this->repository->findById('123');
        $this->assertEquals(1050.0, $account->getBalance());
    }
}
