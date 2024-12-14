<?php
declare(strict_types=1);

namespace App\Application\Enums;

enum CurrencyEnum: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case PLN = 'PLN'; // Add more currencies as needed
}
