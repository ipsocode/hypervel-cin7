<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * Whether a customer or supplier is in use.
 *
 * @see docs/data.md
 */
enum RecordStatus: string
{
    case Active = 'Active';
    case Deprecated = 'Deprecated';
}
