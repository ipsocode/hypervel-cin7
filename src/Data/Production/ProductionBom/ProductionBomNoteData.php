<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionBOMNote, a note of a BOM operation. `NoteID` is required when updating.
 *
 * @see docs/data.md
 */
final class ProductionBomNoteData extends Data
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
