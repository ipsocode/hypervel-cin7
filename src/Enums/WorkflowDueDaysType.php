<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * What a workflow step's days are counted from.
 *
 * @see docs/data.md
 */
enum WorkflowDueDaysType: string
{
    case CompletedDate = 'COMPLETED_DATE';
    case StartDate = 'START_DATE';
}
