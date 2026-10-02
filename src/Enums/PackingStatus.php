<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A sale's combined pack status, over all its fulfilments.
 *
 * @see docs/data.md
 */
enum PackingStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case NotPacked = 'NOT PACKED';
    case Packing = 'PACKING';
    case PartiallyPacked = 'PARTIALLY PACKED';
    case Packed = 'PACKED';
    case Voided = 'VOIDED';
}
