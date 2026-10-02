<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractAddressData;

/**
 * Address Model, the billing address of a sale or a purchase. The table requires `Line1` and
 * `Country`, but every `purchase` response example sends a billing address with neither (`null`),
 * even after a POST that sent them, so both are optional.
 *
 * @see docs/data.md
 */
final class AddressData extends AbstractAddressData
{
    #[Uuid]
    public ?string $ID = null;

    #[Max(256)]
    public ?string $Line1 = null;

    #[Max(256)]
    public ?string $Country = null;
}
