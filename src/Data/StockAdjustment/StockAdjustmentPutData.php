<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockAdjustment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\Other\NewStockLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The body of `stockadjustment` PUT: the Stock Adjustment POST/PUT table with the `TaskID` PUT
 * requires. The POST body is `StockAdjustmentPostData`.
 *
 * @see docs/data.md
 */
final class StockAdjustmentPutData extends AbstractStockAdjustmentData
{
    /**
     * @param list<NewStockLineData> $Lines
     * @param null|bool $UpdateOnHand adjust the quantity on hand, not the available quantity
     */
    public function __construct(
        string $EffectiveDate,
        CompletionStatus $Status,
        #[DataCollectionOf(NewStockLineData::class)]
        public array $Lines,
        #[Uuid]
        public string $TaskID,
        public ?bool $UpdateOnHand = null,
    ) {
        parent::__construct($EffectiveDate, $Status);
    }
}
