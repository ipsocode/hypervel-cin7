<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

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
        public string|Optional $ID,
        public string|Optional $CustomerID,
        public string|Optional $Line1,
        public string|Optional|null $Line2,
        public string|Optional|null $City,
        public string|Optional|null $State,
        public string|Optional|null $Postcode,
        public string|Optional $Country,
        public string|Optional $Type,
        public bool|Optional $DefaultForType,
    ) {
    }
}
