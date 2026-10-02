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
        public ?string $ID = null,
        #[Max(50)]
        public ?string $Name = null,
        public float|string|null $Percent = null,
        public ?string $AccountCode = null,
        public string|int|null $Compound = null,
        public int|string|null $ComponentOrder = null,
    ) {
    }
}
