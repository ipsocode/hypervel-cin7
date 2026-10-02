<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\PutAway;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Advanced Purchase Put Away Model and the Available Fields for Purchase Put Away
 * table share: a put away task of an advanced purchase, as `advanced-purchase/put-away` answers
 * it and its POST takes it. Each is a final child that adds its own `PurchaseID` and `TaskID`.
 *
 * Both tables require the `Status` and `Lines`, so each child passes them to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractAdvancedPurchasePutAwayData extends Data
{
    /**
     * @param list<AdvancedPurchasePutAwayLineData> $Lines
     */
    public function __construct(
        public TaskStatus $Status,
        #[DataCollectionOf(AdvancedPurchasePutAwayLineData::class)]
        public array $Lines,
    ) {
    }
}
