<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A sale's combined payment status.
 *
 * @see docs/data.md
 */
enum SalePaymentStatus: string
{
    case Unpaid = 'UNPAID';
    case Prepaid = 'PREPAID';
    case PartiallyPaid = 'PARTIALLY PAID';
    case Paid = 'PAID';
    case NotRefunded = 'NOT REFUNDED';
    case Voided = 'VOIDED';
}
