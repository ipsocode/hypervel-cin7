<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of task a co-manufacturing operation starts.
 *
 * @see docs/data.md
 */
enum ProductionRunCoManTaskType: string
{
    case PurchaseTask = 'PurchaseTask';
    case SaleTask = 'SaleTask';
    case TransferTask = 'TransferTask';
}
