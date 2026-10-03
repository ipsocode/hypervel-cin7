<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Quote;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Quote Model, a sale's `Quote`, and the Sale Quote table, the response of `sale/quote`, which
 * adds `SaleID` and `CombineAdditionalCharges`. One name, so one class: the table requires those
 * two and a sale's embedded `Quote` has neither, so both are nullable and `#[Required]`, which only
 * a write body checks. The POST body is `SaleQuotePostData`.
 *
 * @see docs/data.md
 */
final class SaleQuoteData extends AbstractSaleQuoteData implements WithResponse
{
    use HasResponse;

    /**
     * @param list<SaleQuoteLineData> $Lines
     */
    public function __construct(
        string $Memo,
        TaskStatus $Status,
        array $Lines,
        public float $TotalBeforeTax,
        public float $Tax,
        public float $Total,
        #[Required]
        #[Uuid]
        public ?string $SaleID = null,
        #[Required]
        public ?bool $CombineAdditionalCharges = null,
    ) {
        parent::__construct($Memo, $Status, $Lines);
    }
}
