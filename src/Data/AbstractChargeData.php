<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields the additional charge models of sale and purchase documents share, with the same
 * types and lengths in every table: the Sale and Sale Invoice Additional Charge Models, and the
 * Purchase and Purchase Invoice Additional Charge Models. Each model is a final child that adds
 * its own fields (`Comment` on sale charges, `Reference` on purchase charges, `Account` on
 * invoice charges).
 *
 * Every one of those tables marks the five constructor fields required, so each child takes them
 * through this constructor; the optional fields are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractChargeData extends Data
{
    public ?float $Discount = null;

    public ?float $Total = null;

    public function __construct(
        #[Max(256)]
        public string $Description,
        public float $Quantity,
        public float $Price,
        public float $Tax,
        #[Max(50)]
        public string $TaxRule,
    ) {
    }
}
