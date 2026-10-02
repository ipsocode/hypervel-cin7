<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a sale's fulfilment.
 *
 * @see docs/data.md
 */
enum FulfilmentStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case NotFulfilled = 'NOT FULFILLED';
    case Fulfilled = 'FULFILLED';
    case PartiallyFulfilled = 'PARTIALLY FULFILLED';
    case Voided = 'VOIDED';
}
