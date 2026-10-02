<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a finished goods task.
 *
 * @see docs/data.md
 */
enum FinishedGoodsStatus: string
{
    case Draft = 'DRAFT';
    case Authorised = 'AUTHORISED';
    case InProgress = 'IN PROGRESS';
    case Completed = 'COMPLETED';
    case Voided = 'VOIDED';
}
