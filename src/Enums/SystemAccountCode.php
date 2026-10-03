<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The system account an account in the chart of accounts stands for, by code; `SystemAccount`
 * names the same account by its name.
 *
 * @see docs/data.md
 */
enum SystemAccountCode: string
{
    case BankCurrencyGain = 'BANKCURRENCYGAIN';
    case Creditors = 'CREDITORS';
    case Debtors = 'DEBTORS';
    case Gst = 'GST';
    case GstOnImports = 'GSTONIMPORTS';
    case Historical = 'HISTORICAL';
    case RealisedCurrencyGain = 'REALISEDCURRENCYGAIN';
    case RetainedEarnings = 'RETAINEDEARNINGS';
    case Rounding = 'ROUNDING';
    case TrackingTransfers = 'TRACKINGTRANSFERS';
    case UnpaidExpenseClaims = 'UNPAIDEXPCLM';
    case UnrealisedCurrencyGain = 'UNREALISEDCURRENCYGAIN';
    case WagePayables = 'WAGEPAYABLES';
}
