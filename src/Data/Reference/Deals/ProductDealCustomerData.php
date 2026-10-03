<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Deal Customer, one entry of a deal's `DealCustomers`: a customer by `CustomerID` or
 * `CustomerName`, and the `ID` of the link. The table writes all three as "Yes*", so none is
 * required.
 *
 * @see docs/data.md
 */
final class ProductDealCustomerData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $CustomerName = null,
        #[Uuid]
        public ?string $CustomerID = null,
    ) {
    }
}
