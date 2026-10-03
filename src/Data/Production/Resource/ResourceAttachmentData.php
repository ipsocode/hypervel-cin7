<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ResourceAttachment, a file attached to a resource: base64 `Content`. `AttachmentID`, `Date` and
 * `ContentType` are read-only. The table requires `Content`, and the examples send it, but every
 * other attachment's response leaves its content `null`, so it is nullable, for the responses, and
 * `#[Required]`, which only a write body checks.
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
        #[Required]
        public ?string $Content = null,
    ) {
    }
}
