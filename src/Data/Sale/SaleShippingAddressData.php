<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

/**
 * Sale Shipping Address Model.
 *
 * @see docs/data.md
 */
final class SaleShippingAddressData extends AbstractAddressData
{
    public function __construct(
        public ?string $Company = null,
        public ?string $Contact = null,
        public ?bool $ShipToOther = null,
    ) {
    }
}
