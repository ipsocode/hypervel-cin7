<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Quote;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `sale/quote` POST: the Sale Quote table with the `SaleID` and
 * `CombineAdditionalCharges` it requires, a `Status` of `DRAFT` or `AUTHORISED`, and the optional
 * totals `TotalBeforeTax`, `Tax` and `Total`, which Cin7 computes from the lines. The response is
 * `SaleQuoteData`.
 *
 * @see docs/data.md
 */
final class SaleQuotePostData extends AbstractSaleQuoteData
{
    /**
     * @param list<SaleQuoteLineData> $Lines
     */
    public function __construct(
        string $Memo,
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        array $Lines,
        #[Uuid]
        public string $SaleID,
        public bool $CombineAdditionalCharges,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
    ) {
        parent::__construct($Memo, $Status, $Lines);
    }
}
