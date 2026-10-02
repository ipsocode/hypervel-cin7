<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Quote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SalePaymentLineData;

/**
 * Sale Quote Model.
 *
 * @see docs/data.md
 */
final class SaleQuoteData extends Data
{
    /**
     * @param null|list<SalePaymentLineData> $Prepayments
     * @param null|list<SaleQuoteLineData> $Lines
     * @param null|list<SaleAdditionalChargeData> $AdditionalCharges
     */
    public function __construct(
        #[Max(1024)]
        public ?string $Memo = null,
        public ?string $Status = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Prepayments = null,
        #[DataCollectionOf(SaleQuoteLineData::class)]
        public ?array $Lines = null,
        #[DataCollectionOf(SaleAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
    ) {
    }
}
