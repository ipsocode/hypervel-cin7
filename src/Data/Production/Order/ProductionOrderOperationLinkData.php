<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderOperationLink, a read-only link from a production order operation to a related
 * one.
 *
 * @see docs/data.md
 */
final class ProductionOrderOperationLinkData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Uuid]
        public ?string $RelatedOperationID = null,
        public ?int $Position = null,
        public ?int $RelationType = null,
    ) {
    }
}
