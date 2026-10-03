<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/attachment` PUT: the `AttachmentID` and whether it `IsProcessed`.
 *
 * @see docs/data.md
 */
final class ProductionOrderAttachmentPutData extends Data
{
    public function __construct(
        #[Uuid]
        public string $AttachmentID,
        public ?bool $IsProcessed = null,
    ) {
    }
}
