<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a task stage, the reference's Quote Statuses: a quote, a pick, a pack, a credit
 * note, a manual journal and the purchase stages.
 *
 * @see docs/data.md
 */
enum TaskStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case Draft = 'DRAFT';
    case Authorised = 'AUTHORISED';
    case Voided = 'VOIDED';
}
