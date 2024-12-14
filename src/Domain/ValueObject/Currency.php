<?php

namespace App\Domain\ValueObject;

use App\Application\Enums\CurrencyEnum;

readonly final class Currency
{
    public function __construct(
        private CurrencyEnum $code
    ) {}

    public function equals(Currency $currency): bool
    {
        return $this->code === $currency->code;
    }

    public function getCode(): CurrencyEnum
    {
        return $this->code;
    }
}
