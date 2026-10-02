<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Data;

/**
 * Child Customer Model, one entry of a customer's `ChildCustomers` (responses only).
 *
 * @see docs/data.md
 */
final class ChildCustomerData extends Data
{
    public function __construct(
        public ?string $CustomerID = null,
        public ?string $CustomerName = null,
    ) {
    }
}
