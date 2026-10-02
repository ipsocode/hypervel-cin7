<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The class of an account in the chart of accounts.
 *
 * @see docs/data.md
 */
enum AccountClass: string
{
    case Asset = 'ASSET';
    case Liability = 'LIABILITY';
    case Expense = 'EXPENSE';
    case Equity = 'EQUITY';
    case Revenue = 'REVENUE';
}
