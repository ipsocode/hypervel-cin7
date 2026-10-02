<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a disassembly task.
 *
 * @see docs/data.md
 */
enum DisassemblyStatus: string
{
    case Draft = 'DRAFT';
    case WorkInProgress = 'WORK IN PROGRESS';
    case Completed = 'COMPLETED';
    case Voided = 'VOIDED';
}
