<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Attachment;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `sale/attachment` POST, the reference's "Available fields for POST Methods": a file
 * for the sale, given as base64 `Content` or as a `FileDownloadUrl` Cin7 fetches it from; a body
 * with neither fails validation before it is sent.
 *
 * @see docs/data.md
 */
final class SaleAttachmentPostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $SaleID,
        public string $FileName,
        public ?string $FileDownloadUrl = null,
        #[RequiredWithout('FileDownloadUrl')]
        public ?string $Content = null,
    ) {
    }
}
