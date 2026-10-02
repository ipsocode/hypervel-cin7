<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Ship;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleShippingAddressData;
use Ipsocode\Cin7\Enums\ShipmentStatus;

/**
 * The fields of the Sale Fulfilment Ship table as `sale/fulfilment/ship` POST and PUT take them:
 * the bodies `SaleFulfilmentShipPostData` and `SaleFulfilmentShipPutData`. The response is
 * `SaleFulfilmentShipData`.
 *
 * Both verbs require the fulfilment's `TaskID` and a `Status` of `DRAFT`, `PARTIALLY AUTHORISED`
 * or `AUTHORISED`, so each child passes them to this constructor. A line names its box `Box`, not
 * `Boxes` as a response does.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleFulfilmentShipTaskData extends Data
{
    #[Date]
    public ?string $RequireBy = null;

    public ?SaleShippingAddressData $ShippingAddress = null;

    #[Max(1024)]
    public ?string $ShippingNotes = null;

    /**
     * @var null|list<SaleFulfilmentShipLinePostPutData>
     */
    #[DataCollectionOf(SaleFulfilmentShipLinePostPutData::class)]
    public ?array $Lines = null;

    public function __construct(
        #[Uuid]
        public string $TaskID,
        #[In(ShipmentStatus::Draft, ShipmentStatus::PartiallyAuthorised, ShipmentStatus::Authorised)]
        public ShipmentStatus $Status,
    ) {
    }
}
