<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionBOMAttachment, a file attached to a BOM operation: base64 `Content` and its
 * `ContentType`. `AttachmentID` is required when updating; `Date` is read-only.
 *
 * @see docs/data.md
 */
final class ProductionBomAttachmentData extends Data
{
    public function __construct(
        public int $Position,
        #[Uuid]
        public ?string $AttachmentID = null,
        #[Max(256)]
        public ?string $FileName = null,
        #[DateTime]
        public ?string $Date = null,
        public ?string $ContentType = null,
        public ?string $Content = null,
    ) {
    }
}
