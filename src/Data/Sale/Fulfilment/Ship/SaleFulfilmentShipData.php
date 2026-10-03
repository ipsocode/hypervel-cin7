<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Ship;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Sale\SaleShippingAddressData;
use Ipsocode\Cin7\Enums\ShipmentStatus;

/**
 * Sale Fulfilment Ship Model, a fulfilment's `Ship`, and Sale Fulfilment Ship, the response of
 * every `sale/fulfilment/ship` action, which adds the fulfilment's `TaskID`. One name, so one
 * class: the table requires `TaskID` and a sale's embedded `Ship` has none, so it is nullable and
 * `#[Required]`, which only a write body checks. The bodies of POST and PUT are
 * `SaleFulfilmentShipPostData` and `SaleFulfilmentShipPutData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<SaleFulfilmentShipLineData> $Lines
     */
    public function __construct(
        public ShipmentStatus $Status,
        #[Required]
        #[Uuid]
        public ?string $TaskID = null,
        #[Date]
        public ?string $RequireBy = null,
        public ?SaleShippingAddressData $ShippingAddress = null,
        #[Max(1024)]
        public ?string $ShippingNotes = null,
        #[DataCollectionOf(SaleFulfilmentShipLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
