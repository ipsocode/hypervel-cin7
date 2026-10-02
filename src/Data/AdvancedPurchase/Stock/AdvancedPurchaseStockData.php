<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced Purchase Stock Model, one stock receiving task of an advanced purchase (an item of the
 * `StockReceiving` that every `advanced-purchase/stock` action answers with), and the Available
 * Fields for Purchase Stock Received table, which adds the purchase's `PurchaseID`. One name, so
 * one class: `PurchaseID` is optional, as only the second table has it, and `TaskID` is required,
 * as the model requires it. The bodies of POST and PUT are `AdvancedPurchaseStockPostData` and
 * `AdvancedPurchaseStockPutData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseStockData extends AbstractAdvancedPurchaseStockData
{
    /**
     * @param list<AdvancedPurchaseStockLineData> $Lines
     */
    public function __construct(
        TaskStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
        #[Uuid]
        public ?string $PurchaseID = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
