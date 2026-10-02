<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Attachment Line Model, shared by sale, moneyOperation and product.
 *
 * @see docs/data.md
 */
final class AttachmentLineData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $ContentType,
        public bool|Optional $IsDefault,
        public string|Optional $FileName,
        public string|Optional $DownloadUrl,
    ) {
    }
}
