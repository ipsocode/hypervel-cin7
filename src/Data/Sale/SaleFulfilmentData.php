<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Fulfilment Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentData extends Data
{
    public function __construct(
        public string|Optional $TaskID,
        public int|Optional $FulfillmentNumber,
        public string|Optional $LinkedInvoiceNumber,
        public string|Optional $FulFilmentStatus,
        public SaleFulfilmentPickPackData|Optional $Pick,
        public SaleFulfilmentPickPackData|Optional $Pack,
        public SaleFulfilmentShipData|Optional $Ship,
    ) {
    }
}
