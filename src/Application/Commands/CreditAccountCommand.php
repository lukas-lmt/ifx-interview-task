<?php
declare(strict_types=1);

namespace App\Application\Commands;

use App\Application\Enums\CurrencyEnum;

readonly final class CreditAccountCommand
{
    public function __construct(
        public string $accountId,
        public float $amount,
        public CurrencyEnum $currency
    ) {}
}