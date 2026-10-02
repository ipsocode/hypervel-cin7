<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Enums\FulfilmentStatus;

/**
 * Sale Fulfilment Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?int $FulfillmentNumber = null,
        public ?string $LinkedInvoiceNumber = null,
        public ?FulfilmentStatus $FulFilmentStatus = null,
        public ?SaleFulfilmentPickPackData $Pick = null,
        public ?SaleFulfilmentPickPackData $Pack = null,
        public ?SaleFulfilmentShipData $Ship = null,
    ) {
    }
}
