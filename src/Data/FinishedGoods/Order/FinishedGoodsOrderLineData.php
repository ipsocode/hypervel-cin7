<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Finished Goods Order Line Model, a component line of a finished goods order: a product, found by
 * `ProductID` or `ProductCode`, with its `Quantity`. `Name`, `Unit` and `TotalQuantity` are
 * read-only, `ExpenseAccount` and `TotalCost` are required for a service product, and
 * `WastagePercent` and `WastageQuantity` are mutually exclusive.
 *
 * @see docs/data.md
 */
final class FinishedGoodsOrderLineData extends Data
{
    public function __construct(
        public float $Quantity,
        #[RequiredWithout('ProductCode')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        #[Max(256)]
        public ?string $ProductCode = null,
        #[Max(256)]
        public ?string $Name = null,
        public ?string $ExpenseAccount = null,
        public ?string $Unit = null,
        public ?float $WastagePercent = null,
        public ?float $WastageQuantity = null,
        public ?float $TotalQuantity = null,
        public ?float $TotalCost = null,
    ) {
    }
}
