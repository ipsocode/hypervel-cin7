<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Sale\SaleAdditionalChargeData;
use Ipsocode\Cin7\Enums\OrderStatus;

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
     * @param list<SaleOrderLineData> $Lines
     * @param null|list<SaleAdditionalChargeData> $AdditionalCharges
     */
    public function __construct(
        #[Max(1024)]
        public string $Memo,
        #[In(OrderStatus::Draft, OrderStatus::Authorised)]
        public OrderStatus $Status,
        #[DataCollectionOf(SaleOrderLineData::class)]
        public array $Lines,
        public float $TotalBeforeTax,
        public float $Tax,
        public float $Total,
        #[Uuid]
        public ?string $SaleID = null,
        #[Max(256)]
        public ?string $SaleOrderNumber = null,
        public ?bool $CombineAdditionalCharges = null,
        #[DataCollectionOf(SaleAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
        public ?string $AutoPickPackShipMode = null,
    ) {
    }
}
