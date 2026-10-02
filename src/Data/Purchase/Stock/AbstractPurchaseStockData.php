<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Stock;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Purchase Stock Model and the Available Fields for Purchase Stock Received table
 * share: a purchase's `StockReceived`, the response of `purchase/stock` and the body of its POST.
 * Each is a final child that adds its `TaskID`.
 *
 * Both tables require the `Status` and the `Lines`, so each child passes them to this
 * constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseStockData extends Data
{
    /**
     * @param list<PurchaseStockLineData> $Lines
     */
    public function __construct(
        public TaskStatus $Status,
        #[DataCollectionOf(PurchaseStockLineData::class)]
        public array $Lines,
    ) {
    }
}
