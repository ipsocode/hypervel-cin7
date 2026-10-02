<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of an invoice, the reference's Invoice Statuses; also a sale's combined invoice
 * status.
 *
 * @see docs/data.md
 */
enum InvoiceStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case Draft = 'DRAFT';
    case Authorised = 'AUTHORISED';
    case Voided = 'VOIDED';
    case Paid = 'PAID';
}
