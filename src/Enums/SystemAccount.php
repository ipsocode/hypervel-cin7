<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The system account an account in the chart of accounts stands for, by name; `SystemAccountCode`
 * names the same account by its code.
 *
 * @see docs/data.md
 */
enum SystemAccount: string
{
    case AccountsReceivable = 'Accounts receivable';
    case AccountsPayable = 'Accounts payable';
    case BankRevaluations = 'Bank revaluations';
    case GstVat = 'GST / VAT';
    case GstOnImports = 'GST on imports';
    case HistoricalAdjustment = 'Historical adjustment';
    case RealizedCurrencyGains = 'Realized currency gains';
    case RetainedEarnings = 'Retained earnings';
    case Rounding = 'Rounding';
    case TrackingTransfers = 'Tracking transfers';
    case UnpaidExpenseClaims = 'Unpaid expense claims';
    case UnrealizedCurrencyGains = 'Unrealized currency gains';
    case WagesPayable = 'Wages payable';
}
