<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractAddressData;

/**
 * Sale Shipping Address Model.
 *
 * @see docs/data.md
 */
final class SaleShippingAddressData extends AbstractAddressData
{
    #[Max(128)]
    public ?string $Company = null;

    #[Max(512)]
    public ?string $Contact = null;

    public ?bool $ShipToOther = null;
}
