<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderOperationNote, a note of a production order operation. `NoteID` is required when
 * updating.
 *
 * @see docs/data.md
 */
final class ProductionOrderOperationNoteData extends Data
{
    public function __construct(
        public int $Position,
        #[Uuid]
        public ?string $NoteID = null,
        #[Max(4000)]
        public ?string $Note = null,
    ) {
    }
}
