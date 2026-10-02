<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Quote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SalePaymentLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Quote Model.
 *
 * @see docs/data.md
 */
final class SaleQuoteData extends Data
{
    /**
     * @param null|list<SalePaymentLineData> $Prepayments
     * @param list<SaleQuoteLineData> $Lines
     * @param null|list<SaleAdditionalChargeData> $AdditionalCharges
     */
    public function __construct(
        #[Max(1024)]
        public string $Memo,
        public TaskStatus $Status,
        #[DataCollectionOf(SaleQuoteLineData::class)]
        public array $Lines,
        public float $TotalBeforeTax,
        public float $Tax,
        public float $Total,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Prepayments = null,
        #[DataCollectionOf(SaleAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
    ) {
    }
}
