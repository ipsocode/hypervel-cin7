<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A sale's combined pick status, over all its fulfilments.
 *
 * @see docs/data.md
 */
enum PickingStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case NotPicked = 'NOT PICKED';
    case Picking = 'PICKING';
    case PartiallyPicked = 'PARTIALLY PICKED';
    case Picked = 'PICKED';
    case Voided = 'VOIDED';
}
