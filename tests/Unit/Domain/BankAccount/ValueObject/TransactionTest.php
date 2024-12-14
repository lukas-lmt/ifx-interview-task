<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use App\Domain\BankAccount\ValueObject\Transaction;
use App\Domain\BankAccount\Enums\TransactionTypeEnum;

class TransactionTest extends TestCase
{
    public function testTransactionCreation(): void
    {
        $transaction = new Transaction(TransactionTypeEnum::CREDIT, 100.0, 0.0);

        $this->assertEquals(TransactionTypeEnum::CREDIT, $transaction->getType());
        $this->assertEquals(100.0, $transaction->getAmount());
        $this->assertEquals(0.0, $transaction->getFee());
        $this->assertInstanceOf(\DateTimeImmutable::class, $transaction->getDate());
    }

    public function testTransactionCreationThrowsExceptionForInvalidAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Transaction amount must be positive.");

        new Transaction(TransactionTypeEnum::CREDIT, -100.0, 0.0);
    }
}
