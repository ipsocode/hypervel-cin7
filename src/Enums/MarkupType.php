<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a markup price is calculated from its start price: `P` adds `MarkupValue` per cent, `A` adds
 * `MarkupValue` itself, and `D` means the tier has no markup (in a PUT, that it is deleted).
 *
 * @see docs/data.md
 */
enum MarkupType: string
{
    case Percent = 'P';
    case Amount = 'A';
    case Deleted = 'D';
}
