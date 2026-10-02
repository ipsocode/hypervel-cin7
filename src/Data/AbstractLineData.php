<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Concerns\HasProductFields;

/**
 * The fields the product line models of sale and purchase documents share, with the same types
 * and lengths in every table: the Sale Quote, Sale Order and Sale Invoice Line Models, and the
 * Purchase Order and Purchase Invoice Line Models. With them come the product fields every
 * object with a `ProductID` carries. Each model is a final child that adds its own fields
 * (`AverageCost` on sale lines, `Account` on invoice lines); a field is set through `from()`,
 * as the children have no constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractLineData extends Data
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

    public ?string $TaxRule = null;

    public ?string $Comment = null;
}
