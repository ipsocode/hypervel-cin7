<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
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
        public ?string $TaskID = null,
        public ?string $ProductID = null,
        public ?string $Date = null,
        public ?float $COGS = null,
    ) {
    }
}
