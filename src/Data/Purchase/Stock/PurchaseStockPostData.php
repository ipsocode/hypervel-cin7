<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Stock;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `purchase/stock` POST: the Available Fields for Purchase Stock Received table with
 * the `TaskID` it requires and a `Status` of `DRAFT` or `AUTHORISED`. POST adds only new lines;
 * empty `Lines` with `AUTHORISED` authorise the stock received. The response is
 * `PurchaseStockData`.
 *
 * @see docs/data.md
 */
final class PurchaseStockPostData extends AbstractPurchaseStockData
{
    /**
     * @param list<PurchaseStockLineData> $Lines
     */
    public function __construct(
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status, $Lines);
    }
}
