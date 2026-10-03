<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a customer, supplier or company address.
 *
 * @see docs/data.md
 */
enum AddressType: string
{
    case Billing = 'Billing';
    case Business = 'Business';
    case Shipping = 'Shipping';
}
