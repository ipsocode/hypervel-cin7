<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Attachment Line Model, shared by the sale, money task, journal and product families. The
 * attachment endpoints answer a bare list of these, so `dto()` keeps the response on each.
 *
 * @see docs/data.md
 */
final class AttachmentLineData extends Data implements WithResponse
{
    use HasResponse;

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
