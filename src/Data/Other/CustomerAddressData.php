<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\AddressType;

/**
 * Customer Address Model (the reference's Supplier/Customer Address Model, SupplierAddressModel),
 * one entry of a customer's or a supplier's `Addresses`.
 *
 * The reference's examples also carry the owner's `CustomerID` or `SupplierID` on each address.
 *
 * @see docs/data.md
 */
final class CustomerAddressData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $Line1,
        public string $Country,
        public AddressType $Type,
        #[Uuid]
        public ?string $ID = null,
        public ?string $CustomerID = null,
        public ?string $SupplierID = null,
        #[Max(256)]
        public ?string $Line2 = null,
        #[Max(256)]
        public ?string $City = null,
        #[Max(256)]
        public ?string $State = null,
        #[Max(20)]
        public ?string $Postcode = null,
        public ?bool $DefaultForType = null,
    ) {
    }
}
