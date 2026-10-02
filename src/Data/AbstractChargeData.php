<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;

/**
 * The fields the additional charge models of sale and purchase documents share, with the same
 * types and lengths in every table: the Sale and Sale Invoice Additional Charge Models, and the
 * Purchase and Purchase Invoice Additional Charge Models. Each model is a final child that adds
 * its own fields (`Comment` on sale charges, `Reference` on purchase charges, `Account` on
 * invoice charges); a field is set through `from()`, as the children have no constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractChargeData extends Data
{
    public ?string $Description = null;

    public ?float $Quantity = null;

    public ?float $Price = null;

    public ?float $Discount = null;

    public ?float $Tax = null;

    public ?float $Total = null;

    public ?string $TaxRule = null;
}
