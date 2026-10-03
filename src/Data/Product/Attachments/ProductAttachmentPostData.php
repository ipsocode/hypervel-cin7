<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product\Attachments;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `product/attachments` POST, the reference's "Available fields for POST Methods": a file for the
 * product, given as base64 `Content` or as a `FileDownloadUrl` Cin7 fetches it from; a body with
 * neither fails validation before it is sent. `IsDefault` makes an image the default one.
 *
 * @see docs/data.md
 */
final class ProductAttachmentPostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ProductID,
        public string $FileName,
        public ?bool $IsDefault = null,
        public ?string $FileDownloadUrl = null,
        #[RequiredWithout('FileDownloadUrl')]
        public ?string $Content = null,
    ) {
    }
}
