<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A purchase's combined receiving status: how much of its stock is received and put away.
 *
 * @see docs/data.md
 */
enum ReceivingStatus: string
{
    case FullyReceived = 'FULLY RECEIVED';
    case PartiallyReceived = 'PARTIALLY RECEIVED';
    case NotAvailable = 'NOT AVAILABLE';
    case NotReceived = 'NOT RECEIVED';
    case Voided = 'VOIDED';
}
