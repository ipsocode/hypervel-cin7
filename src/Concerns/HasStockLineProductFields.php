<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Concerns;

use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Enums\CostingMethod;

/**
 * The product fields the reference adds to the lines of a stock adjustment and a stock take, on
 * top of `HasProductFields`: `Image`, `Barcode`, `StockLocator`, `Unit` and `CostingMethod`. All
 * are read-only, and "available for stock take".
 *
 * @see docs/data.md
 */
trait HasStockLineProductFields
{
    public ?AttachmentLineData $Image = null;

    public ?string $Barcode = null;

    public ?string $StockLocator = null;

    public ?string $Unit = null;

    public ?CostingMethod $CostingMethod = null;
}
