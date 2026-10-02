<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `advanced-purchase/stock` POST: the Available Fields for Purchase Stock Received
 * table, with the `PurchaseID` it requires and a `Status` of `DRAFT` or `AUTHORISED`. Without a
 * `TaskID`, or with the empty GUID, POST creates a new stock receiving task; it only adds lines,
 * and a POST with `Status` `AUTHORISED` and empty `Lines` authorises the task. The response is
 * `AdvancedPurchaseStocksData`; the PUT body is `AdvancedPurchaseStockPutData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseStockPostData extends AbstractAdvancedPurchaseStockData
{
    /**
     * @param list<AdvancedPurchaseStockLineData> $Lines
     */
    public function __construct(
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        array $Lines,
        #[Uuid]
        public string $PurchaseID,
        #[Uuid]
        public ?string $TaskID = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
