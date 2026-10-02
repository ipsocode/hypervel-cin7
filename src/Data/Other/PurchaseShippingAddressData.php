<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Ipsocode\Cin7\Data\AbstractAddressData;

/**
 * Purchase Shipping Address Model, the shipping address of a purchase and an advanced purchase:
 * the Address Model's fields except its `ID`, with `ShipToOther` and the `Company` it ships to.
 * The table requires `Line1` and `Country`, but the `purchase` GET example sends a shipping
 * address with neither (`null`). So both are nullable and `#[Required]`, as on `AddressData`: a
 * response may leave them out, a write body may not.
 *
 * @see docs/data.md
 */
final class PurchaseShippingAddressData extends AbstractAddressData
{
    #[Required]
    #[Max(256)]
    public ?string $Line1 = null;

    #[Required]
    #[Max(256)]
    public ?string $Country = null;

    public ?bool $ShipToOther = null;

    #[Max(128)]
    public ?string $Company = null;
}
