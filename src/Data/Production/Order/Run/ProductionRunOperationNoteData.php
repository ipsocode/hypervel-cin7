<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionRunOperationNote, a note of a run operation. `NoteID` is required when updating.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationNoteData extends Data
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
