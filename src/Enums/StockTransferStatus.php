<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a stock transfer.
 *
 * @see docs/data.md
 */
enum StockTransferStatus: string
{
    case Draft = 'DRAFT';
    case InTransit = 'IN TRANSIT';
    case Completed = 'COMPLETED';
    case Voided = 'VOIDED';
}
