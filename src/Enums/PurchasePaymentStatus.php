<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A purchase's combined payment status.
 *
 * @see docs/data.md
 */
enum PurchasePaymentStatus: string
{
    case Prepaid = 'PREPAID';
    case PartiallyPaid = 'PARTIALLY PAID';
    case Unpaid = 'UNPAID';
    case Paid = 'PAID';
    case OverpaidCredited = 'OVERPAID / CREDITED';
    case Voided = 'VOIDED';
}
