<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionOrderOperationAttachment, a file attached to a production order operation: base64
 * `Content`, required when it is created. `AttachmentID` is required when updating; `Date` is
 * read-only.
 *
 * @see docs/data.md
 */
final class ProductionOrderOperationAttachmentData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $ContentType,
        public int $Position,
        #[Uuid]
        public ?string $AttachmentID = null,
        #[Max(256)]
        public ?string $FileName = null,
        #[DateTime]
        public ?string $Date = null,
        public ?string $Content = null,
    ) {
    }
}
