<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * What a workflow step does when its due date is a holiday.
 *
 * @see docs/data.md
 */
enum WorkflowSkipHoliday: string
{
    case MoveNext = 'move_next';
    case MovePrevious = 'move_previous';
    case Leave = 'leave';
    case PhMoveNext = 'ph_move_next';
    case PhMovePrevious = 'ph_move_previous';
}
