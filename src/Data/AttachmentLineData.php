<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Attachment Line Model, shared by sale, moneyOperation and product.
 *
 * @see docs/data.md
 */
final class AttachmentLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $ContentType = null,
        public ?bool $IsDefault = null,
        public ?string $FileName = null,
        public ?string $DownloadUrl = null,
    ) {
    }
}
