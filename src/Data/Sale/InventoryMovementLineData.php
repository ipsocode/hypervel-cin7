<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Concerns\HasProductFields;

/**
 * Inventory Movement Line Model.
 *
 * @see docs/data.md
 */
final class InventoryMovementLineData extends Data
{
    use HasProductFields;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[Uuid]
        public ?string $ProductID = null,
        #[DateTime]
        public ?string $Date = null,
        public ?float $COGS = null,
    ) {
    }
}
