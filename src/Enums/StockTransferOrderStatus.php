<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a stock transfer order.
 *
 * @see docs/data.md
 */
enum StockTransferOrderStatus: string
{
    case NotAvailable = 'NOT AVAILABLE';
    case Draft = 'DRAFT';
    case Authorised = 'AUTHORISED';
}
