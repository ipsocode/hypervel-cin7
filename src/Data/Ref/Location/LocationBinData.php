<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Location;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Location Bin, one entry of a location's `Bins`: a bin, by `ID` and `Name`, whether it is
 * deprecated and whether it is staging. The table only says "Array (ID, Name)"; the examples carry
 * the other two.
 *
 * @see docs/data.md
 */
final class LocationBinData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $Name = null,
        public ?bool $IsDeprecated = null,
        public ?bool $IsStaging = null,
    ) {
    }
}
