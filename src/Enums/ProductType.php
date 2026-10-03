<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * Whether a product is stocked or a service.
 *
 * @see docs/data.md
 */
enum ProductType: string
{
    case Stock = 'Stock';
    case Service = 'Service';
}
