<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `advanced-purchase/stock` PUT: the Available Fields for Purchase Stock Received
 * table, with the `PurchaseID` it requires and a `Status` of `DRAFT` or `AUTHORISED`. A PUT
 * overwrites the stock receiving task it names, so it also requires the `TaskID`, which the table
 * leaves optional for POST. The response is `AdvancedPurchaseStocksData`; the POST body is
 * `AdvancedPurchaseStockPostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseStockPutData extends AbstractAdvancedPurchaseStockData
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
        public string $TaskID,
    ) {
        parent::__construct($Status, $Lines);
    }
}
