<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * The fields the Sale Additional Charge and Sale Invoice Additional Charge Models share. Each
 * model is a final child that adds its own fields; a field is set through `from()`, as the
 * children have no constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleChargeData extends Data
{
    public ?string $Description = null;

    public ?float $Quantity = null;

    public ?float $Price = null;

    public ?float $Discount = null;

    public ?float $Tax = null;

    public ?float $Total = null;

    public ?string $TaxRule = null;

    public ?string $Comment = null;
}
