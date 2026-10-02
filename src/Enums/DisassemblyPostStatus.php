<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status a disassembly POST takes.
 *
 * @see docs/data.md
 */
enum DisassemblyPostStatus: string
{
    case Draft = 'DRAFT';
    case Authorised = 'AUTHORISED';
    case InProgress = 'IN PROGRESS';
    case Completed = 'COMPLETED';
}
