<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * Whether a new sale or purchase is Simple (one invoice, fulfilment or receipt per order) or
 * Advanced (several), sent on POST.
 *
 * @see docs/data.md
 */
enum ProcessType: string
{
    case Simple = 'Simple';
    case Advanced = 'Advanced';
}
