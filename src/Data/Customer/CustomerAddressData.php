<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

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
        public ?string $ID = null,
        public ?string $CustomerID = null,
        public ?string $Line1 = null,
        public ?string $Line2 = null,
        public ?string $City = null,
        public ?string $State = null,
        public ?string $Postcode = null,
        public ?string $Country = null,
        public ?string $Type = null,
        public ?bool $DefaultForType = null,
    ) {
    }
}
