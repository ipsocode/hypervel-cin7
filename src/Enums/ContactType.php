<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a company contact, one of `me/contacts`.
 *
 * @see docs/data.md
 */
enum ContactType: string
{
    case Billing = 'Billing';
    case Business = 'Business';
    case Sale = 'Sale';
    case Shipping = 'Shipping';
    case Employee = 'Employee';
}
