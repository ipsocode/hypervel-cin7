<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A purchase's combined invoice status: how much of it is invoiced, and whether it is credited.
 *
 * @see docs/data.md
 */
enum InvoicingStatus: string
{
    case Invoiced = 'INVOICED';
    case InvoicedCredited = 'INVOICED / CREDITED';
    case NotAvailable = 'NOT AVAILABLE';
    case NotInvoiced = 'NOT INVOICED';
    case PartiallyInvoiced = 'PARTIALLY INVOICED';
    case PartiallyInvoicedCredited = 'PARTIALLY INVOICED / CREDITED';
}
