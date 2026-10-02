<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Address Model, a sale's billing address.
 *
 * @see docs/data.md
 */
final class AddressData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $DisplayAddressLine1 = null,
        public ?string $DisplayAddressLine2 = null,
        public ?string $Line1 = null,
        public ?string $Line2 = null,
        public ?string $City = null,
        public ?string $State = null,
        public ?string $Postcode = null,
        public ?string $Country = null,
    ) {
    }
}
