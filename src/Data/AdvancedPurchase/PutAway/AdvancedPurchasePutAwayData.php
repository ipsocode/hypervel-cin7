<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\PutAway;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced Purchase Put Away Model, one put away task of an advanced purchase (an item of the
 * `PutAway` that every `advanced-purchase/put-away` action answers with), and the Available Fields
 * for Purchase Put Away table, which adds the purchase's `PurchaseID`. One name, so one class:
 * `PurchaseID` is optional, as only the second table has it, and `TaskID` is required, as the
 * model requires it. The POST body is `AdvancedPurchasePutAwayPostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePutAwayData extends AbstractAdvancedPurchasePutAwayData
{
    /**
     * @param list<AdvancedPurchasePutAwayLineData> $Lines
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
