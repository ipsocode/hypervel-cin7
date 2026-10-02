<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Quote Model.
 *
 * @see docs/data.md
 */
final class SaleQuoteData extends Data
{
    /**
     * @param list<SalePaymentLineData>|Optional $Prepayments
     * @param list<SaleQuoteLineData>|Optional $Lines
     * @param list<SaleAdditionalChargeData>|Optional $AdditionalCharges
     */
    public function __construct(
        public string|Optional $Memo,
        public string|Optional $Status,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public array|Optional $Prepayments,
        #[DataCollectionOf(SaleQuoteLineData::class)]
        public array|Optional $Lines,
        #[DataCollectionOf(SaleAdditionalChargeData::class)]
        public array|Optional $AdditionalCharges,
        public float|Optional $TotalBeforeTax,
        public float|Optional $Tax,
        public float|Optional $Total,
    ) {
    }
}
