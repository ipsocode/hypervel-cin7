<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Shipping Address Model.
 *
 * @see docs/data.md
 */
final class SaleShippingAddressData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $DisplayAddressLine1,
        public string|Optional $DisplayAddressLine2,
        public string|Optional $Line1,
        public string|Optional $Line2,
        public string|Optional $City,
        public string|Optional $State,
        public string|Optional $Postcode,
        public string|Optional $Country,
        public string|Optional $Company,
        public string|Optional $Contact,
        public bool|Optional $ShipToOther,
    ) {
    }
}
