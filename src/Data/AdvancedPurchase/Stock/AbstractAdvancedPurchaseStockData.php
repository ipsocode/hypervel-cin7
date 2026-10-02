<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Advanced Purchase Stock Model and the Available Fields for Purchase Stock
 * Received table share: a stock receiving task of an advanced purchase, as
 * `advanced-purchase/stock` answers it and its POST and PUT take it. Each is a final child that
 * adds its own `PurchaseID` and `TaskID`.
 *
 * Both tables require the `Status` and `Lines`, so each child passes them to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractAdvancedPurchaseStockData extends Data
{
    /**
     * @param list<AdvancedPurchaseStockLineData> $Lines
     */
    public function __construct(
        public TaskStatus $Status,
        #[DataCollectionOf(AdvancedPurchaseStockLineData::class)]
        public array $Lines,
    ) {
    }
}
