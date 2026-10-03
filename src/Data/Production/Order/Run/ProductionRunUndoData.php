<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The body and answer of `production/order/run/undo` and `production/order/run/void`: the run, and
 * whether the action applies to its related tasks; the answer adds an `ErrorMessage`.
 *
 * @see docs/data.md
 */
final class ProductionRunUndoData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductionRunID,
        public ?bool $ApplyToRelatedTasks = null,
        public ?string $ErrorMessage = null,
    ) {
    }
}
