<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a stock take.
 *
 * @see docs/data.md
 */
enum StockTakeStatus: string
{
    case Draft = 'DRAFT';
    case InProgress = 'IN PROGRESS';
    case Completed = 'COMPLETED';
    case Voided = 'VOIDED';
}
