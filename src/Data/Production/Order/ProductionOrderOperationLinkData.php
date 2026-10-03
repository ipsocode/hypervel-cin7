<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderOperationLink, a read-only link from a production order operation to a related
 * one. The table requires `Position`, but no example sends it, so it is nullable, for the
 * responses, and `#[Required]`, which only a write body checks.
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
        #[Required]
        public ?int $Position = null,
        public ?int $RelationType = null,
    ) {
    }
}
