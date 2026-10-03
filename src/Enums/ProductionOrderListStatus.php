<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status filter of the production order list.
 *
 * @see docs/data.md
 */
enum ProductionOrderListStatus: string
{
    case AllButVoided = 'AllButVoided';
    case Draft = 'Draft';
    case Planned = 'Planned';
    case Released = 'Released';
    case InProgress = 'InProgress';
    case Completed = 'Completed';
}
