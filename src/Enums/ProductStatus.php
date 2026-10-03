<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a product.
 *
 * @see docs/data.md
 */
enum ProductStatus: string
{
    case Active = 'Active';
    case SetupRequired = 'Setup required';
    case Deprecated = 'Deprecated';
}
