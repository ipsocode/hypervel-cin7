<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a task that completes in one step: a money task, a journal, an inventory
 * write-off.
 *
 * @see docs/data.md
 */
enum CompletionStatus: string
{
    case Draft = 'DRAFT';
    case Completed = 'COMPLETED';
    case Voided = 'VOIDED';
}
