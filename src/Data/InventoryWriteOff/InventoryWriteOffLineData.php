<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOff;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\AbstractStockLineData;

/**
 * Inventory Write-Off Line Model, a line of an inventory write-off's `Lines`: the `Quantity` of a
 * product to write off, found by `ProductID` or `ProductCode`. `ExpenseAccount` and `Cost` are
 * required for a service product, which the line cannot tell, so they stay optional. `TotalCost` is
 * read-only.
 *
 * @see docs/data.md
 */
final class InventoryWriteOffLineData extends AbstractStockLineData
{
    public function __construct(
        float $Quantity,
        public ?string $ExpenseAccount = null,
        public ?float $Cost = null,
        public ?float $TotalCost = null,
    ) {
        parent::__construct($Quantity);
    }
}
