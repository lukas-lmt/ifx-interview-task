<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Commands\CreditAccountCommand;
use App\Application\Commands\DebitAccountCommand;
use App\Application\Enums\CurrencyEnum;
use App\Application\Services\BankAccountService;
use App\Domain\BankAccount\Entity\BankAccount;
use App\Domain\BankAccount\Repository\BankAccountRepositoryInterface;
use App\Domain\BankAccount\ValueObject\Payment;
use App\Domain\Exceptions\BankAccountNotFoundException;
use App\Domain\Services\TransactionFeeCalculator;
use PHPUnit\Framework\TestCase;

class BankAccountServiceTest extends TestCase
{
    private BankAccountService $service;
    private $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(BankAccountRepositoryInterface::class);

        $this->service = new BankAccountService($this->repositoryMock, new TransactionFeeCalculator());
    }

    public function testCreditAccount(): void
    {
        $bankAccount = $this->createMock(BankAccount::class);
        $command = new CreditAccountCommand('123', 100.0, CurrencyEnum::USD);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($command->accountId)
            ->willReturn($bankAccount);

        $bankAccount
            ->expects($this->once())
            ->method('credit')
            ->with($this->isInstanceOf(Payment::class));

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($bankAccount);

        $this->service->creditAccount($command);
    }

    public function testCreditAccountThrowsExceptionWhenAccountNotFound(): void
    {
        $command = new CreditAccountCommand('123', 100.0, CurrencyEnum::USD);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($command->accountId)
            ->willReturn(null);

        $this->expectException(BankAccountNotFoundException::class);

        $this->service->creditAccount($command);
    }

    public function testDebitAccount(): void
    {
        $bankAccount = $this->createMock(BankAccount::class);
        $command = new DebitAccountCommand('123', 50.0, CurrencyEnum::USD);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($command->accountId)
            ->willReturn($bankAccount);

        $bankAccount
            ->expects($this->once())
            ->method('debit')
            ->with($this->isInstanceOf(Payment::class));

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($bankAccount);

        $this->service->debitAccount($command);
    }

    public function testDebitAccountThrowsExceptionWhenAccountNotFound(): void
    {
        $command = new DebitAccountCommand('123', 50.0, CurrencyEnum::USD);

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($command->accountId)
            ->willReturn(null);

        $this->expectException(BankAccountNotFoundException::class);

        $this->service->debitAccount($command);
    }
}
