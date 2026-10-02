<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Order Model, the union with `sale/order`'s "Available Fields for Sale Order" (adds `SaleID` and `CombineAdditionalCharges`).
 *
 * @see docs/data.md
 */
final class SaleOrderData extends Data
{
    /**
     * @param list<SaleOrderLineData>|Optional $Lines
     * @param list<SaleAdditionalChargeData>|Optional $AdditionalCharges
     */
    public function __construct(
        public string|Optional $SaleID,
        public string|Optional $SaleOrderNumber,
        public bool|Optional $CombineAdditionalCharges,
        public string|Optional $Memo,
        public string|Optional $Status,
        #[DataCollectionOf(SaleOrderLineData::class)]
        public array|Optional $Lines,
        #[DataCollectionOf(SaleAdditionalChargeData::class)]
        public array|Optional $AdditionalCharges,
        public float|Optional $TotalBeforeTax,
        public float|Optional $Tax,
        public float|Optional $Total,
    ) {
    }
}
