<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale Order Model, the union with `sale/order`'s "Available Fields for Sale Order" (adds `SaleID` and `CombineAdditionalCharges`), and the body and response of `sale/order`.
 *
 * `AutoPickPackShipMode` is documented only in prose, as a POST option.
 *
 * @see docs/data.md
 */
final class SaleOrderData extends Data implements WithResponse
{
    use HasResponse;

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
        public string|Optional $AutoPickPackShipMode,
    ) {
    }
}
