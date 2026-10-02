<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a sale, the reference's Sale Statuses.
 *
 * @see docs/data.md
 */
enum SaleStatus: string
{
    case Draft = 'DRAFT';
    case Voided = 'VOIDED';
    case Estimating = 'ESTIMATING';
    case Estimated = 'ESTIMATED';
    case Ordering = 'ORDERING';
    case Ordered = 'ORDERED';
    case Backordered = 'BACKORDERED';
    case Picking = 'PICKING';
    case Picked = 'PICKED';
    case Packing = 'PACKING';
    case Packed = 'PACKED';
    case Shipping = 'SHIPPING';
    case Invoicing = 'INVOICING';
    case Invoiced = 'INVOICED';
    case Credited = 'CREDITED';
    case Completed = 'COMPLETED';
}
