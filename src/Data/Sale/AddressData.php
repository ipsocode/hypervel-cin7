<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Address Model, a sale's billing address.
 *
 * @see docs/data.md
 */
final class AddressData extends Data
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
    ) {
    }
}
