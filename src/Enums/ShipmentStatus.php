<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a fulfilment's ship stage.
 *
 * @see docs/data.md
 */
enum ShipmentStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case Draft = 'DRAFT';
    case PartiallyAuthorised = 'PARTIALLY AUTHORISED';
    case Authorised = 'AUTHORISED';
    case Voided = 'VOIDED';
}
