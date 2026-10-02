<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Order;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `purchase/order` POST: the Available Fields for Purchase Order table with the
 * `TaskID` and `CombineAdditionalCharges` it requires, a `Status` of `DRAFT` or `AUTHORISED`, and
 * the totals, which POST does not require. The response is `PurchaseOrderData`.
 *
 * @see docs/data.md
 */
final class PurchaseOrderPostData extends AbstractPurchaseOrderData
{
    /**
     * @param list<PurchaseOrderLineData> $Lines
     */
    public function __construct(
        string $Memo,
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
    ) {
        parent::__construct($Memo, $Status, $Lines);
    }
}
