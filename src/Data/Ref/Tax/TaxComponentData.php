<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * Tax Component Model, one entry of a tax rule's `Components`.
 *
 * The reference types `Percent` as Decimal and `ComponentOrder` as Int, but its examples
 * send both as strings, and `Compound` appears only in the examples.
 *
 * @see docs/data.md
 */
final class TaxComponentData extends Data
{
    public function __construct(
        #[Max(50)]
        public string $Name,
        public float|string $Percent,
        public string $AccountCode,
        public int|string $ComponentOrder,
        public ?string $ID = null,
        public string|int|null $Compound = null,
    ) {
    }
}
