<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\ProductFamily;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Family Product Line Model: a product in a family and the value of each family option it
 * has. `SKU` and `Name` are ignored in a POST or PUT, but the examples send them, so they are
 * modelled and the requests leave them out of the body.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductLineData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ID,
        #[Max(256)]
        public string $Option1,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(256)]
        public ?string $Name = null,
        #[Max(256)]
        public ?string $Option2 = null,
        #[Max(256)]
        public ?string $Option3 = null,
    ) {
    }
}
