<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a production resource.
 *
 * @see docs/data.md
 */
enum ResourceType: string
{
    case Labor = 'Labor';
    case Machine = 'Machine';
    case Other = 'Other';
}
