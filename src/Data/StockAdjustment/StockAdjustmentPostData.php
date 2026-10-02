<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockAdjustment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Ipsocode\Cin7\Data\Other\NewStockLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The body of `stockadjustment` POST: the Stock Adjustment POST/PUT table without the `TaskID`
 * Cin7 assigns. A POST takes `DRAFT` or `COMPLETED`. The PUT body is `StockAdjustmentPutData`.
 *
 * @see docs/data.md
 */
final class StockAdjustmentPostData extends AbstractStockAdjustmentData
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
        public ?bool $UpdateOnHand = null,
    ) {
        parent::__construct($EffectiveDate, $Status);
    }
}
