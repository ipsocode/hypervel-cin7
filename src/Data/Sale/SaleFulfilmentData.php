<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Fulfilment Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentData extends Data
{
    public function __construct(
        public ?string $TaskID = null,
        public ?int $FulfillmentNumber = null,
        public ?string $LinkedInvoiceNumber = null,
        public ?string $FulFilmentStatus = null,
        public ?SaleFulfilmentPickPackData $Pick = null,
        public ?SaleFulfilmentPickPackData $Pack = null,
        public ?SaleFulfilmentShipData $Ship = null,
    ) {
    }
}
