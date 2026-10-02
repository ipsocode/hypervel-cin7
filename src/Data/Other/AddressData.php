<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractAddressData;

/**
 * Address Model, the billing address of a sale or a purchase. The table requires `Line1` and
 * `Country`, but every purchase example sends a billing address with neither (`null`), so both
 * are optional.
 *
 * @see docs/data.md
 */
final class AddressData extends AbstractAddressData
{
    #[Max(256)]
    public ?string $Line1 = null;

    #[Max(256)]
    public ?string $Country = null;
}
