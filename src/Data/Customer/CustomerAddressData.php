<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Customer Address Model (the reference's Supplier/Customer Address Model), one entry of a customer's `Addresses`.
 *
 * The reference's examples also carry `CustomerID` on each address.
 *
 * @see docs/data.md
 */
final class CustomerAddressData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $CustomerID = null,
        #[Max(256)]
        public ?string $Line1 = null,
        #[Max(256)]
        public ?string $Line2 = null,
        #[Max(256)]
        public ?string $City = null,
        #[Max(256)]
        public ?string $State = null,
        #[Max(20)]
        public ?string $Postcode = null,
        public ?string $Country = null,
        public ?string $Type = null,
        public ?bool $DefaultForType = null,
    ) {
    }
}
