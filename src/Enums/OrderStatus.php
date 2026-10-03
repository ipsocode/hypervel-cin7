<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a sale order, the reference's Order Statuses.
 *
 * @see docs/data.md
 */
enum OrderStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case Draft = 'DRAFT';
    case Authorised = 'AUTHORISED';
    case Voided = 'VOIDED';
    case AuthNoAlloc = 'AUTH_NO_ALLOC';
    case Fulfilled = 'FULFILLED';
    case Closed = 'CLOSED';
}
