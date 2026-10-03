<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionBOMAttachment, a file attached to a BOM operation: base64 `Content` and its
 * `ContentType`. `AttachmentID` is required when updating; `Date` is read-only. The table requires
 * `Content`, but a response leaves it `null` unless `ReturnAttachmentsContent` asks for it (the
 * POST response example does), so it is nullable, for the responses, and `#[Required]`, which only
 * a write body checks.
 *
 * @see docs/data.md
 */
final class ProductionBomAttachmentData extends Data
{
    public function __construct(
        public int $Position,
        public string $ContentType,
        #[Uuid]
        public ?string $AttachmentID = null,
        #[Max(256)]
        public ?string $FileName = null,
        #[DateTime]
        public ?string $Date = null,
        #[Required]
        public ?string $Content = null,
    ) {
    }
}
