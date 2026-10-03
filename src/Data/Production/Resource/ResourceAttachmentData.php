<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ResourceAttachment, a file attached to a resource: base64 `Content`. `AttachmentID`, `Date` and
 * `ContentType` are read-only.
 *
 * @see docs/data.md
 */
final class ResourceAttachmentData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $FileName,
        #[Uuid]
        public ?string $AttachmentID = null,
        #[DateTime]
        public ?string $Date = null,
        #[Max(256)]
        public ?string $ContentType = null,
        public ?string $Content = null,
    ) {
    }
}
