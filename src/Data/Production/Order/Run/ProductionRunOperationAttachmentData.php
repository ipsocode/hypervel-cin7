<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunOperationAttachment, a file attached to a run operation: base64 `Content`, required
 * when it is created. `AttachmentID` is required when updating; `Date` and `ContentType` are
 * read-only.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationAttachmentData extends Data
{
    public function __construct(
        public int $Position,
        #[Uuid]
        public ?string $AttachmentID = null,
        #[Max(256)]
        public ?string $FileName = null,
        #[DateTime]
        public ?string $Date = null,
        #[Max(256)]
        public ?string $ContentType = null,
        public ?string $Content = null,
    ) {
    }
}
