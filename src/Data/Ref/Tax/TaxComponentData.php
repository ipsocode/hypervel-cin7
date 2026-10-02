<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

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
        public string|Optional $ID,
        public string|Optional $Name,
        public float|string|Optional $Percent,
        public string|Optional $AccountCode,
        public string|int|Optional $Compound,
        public int|string|Optional $ComponentOrder,
    ) {
    }
}
