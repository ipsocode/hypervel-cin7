<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Concerns\HasProductFields;

/**
 * The fields the Sale Quote Line, Sale Order Line and Sale Invoice Line Models share, with the
 * product fields every object with a `ProductID` carries. Each model is a final child that adds
 * its own fields; a field is set through `from()`, as the children have no constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleLineData extends Data
{
    use HasProductFields;

    public ?string $ProductID = null;

    public ?string $SKU = null;

    public ?string $Name = null;

    public ?float $Quantity = null;

    public ?float $Price = null;

    public ?float $Discount = null;

    public ?float $Tax = null;

    public ?float $Total = null;

    public ?float $AverageCost = null;

    public ?string $TaxRule = null;

    public ?string $Comment = null;
}
