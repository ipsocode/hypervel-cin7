<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A sale's combined ship status, over all its fulfilments.
 *
 * @see docs/data.md
 */
enum ShippingStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case NotShipped = 'NOT SHIPPED';
    case Shipping = 'SHIPPING';
    case PartiallyShipped = 'PARTIALLY SHIPPED';
    case Shipped = 'SHIPPED';
    case Voided = 'VOIDED';
}
