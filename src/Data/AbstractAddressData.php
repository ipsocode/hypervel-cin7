<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields the Address Model, the Sale Shipping Address Model and the Purchase Shipping Address
 * Model share, all optional and set through `from()`. Each table marks `Line1` and `Country`
 * required, but the purchase examples send an address without them (`null`), so each child
 * declares them: `SaleShippingAddressData` requires them, and `AddressData` and
 * `PurchaseShippingAddressData`, which read those examples, leave them optional.
 *
 * @see docs/data.md
 */
abstract class AbstractAddressData extends Data
{
    #[Uuid]
    public ?string $ID = null;

    #[Max(256)]
    public ?string $DisplayAddressLine1 = null;

    #[Max(256)]
    public ?string $DisplayAddressLine2 = null;

    #[Max(256)]
    public ?string $Line2 = null;

    #[Max(256)]
    public ?string $City = null;

    #[Max(256)]
    public ?string $State = null;

    #[Max(20)]
    public ?string $Postcode = null;
}
