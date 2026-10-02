<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOffList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * Inventory Write-Off List, one entry of `InventoryWriteOffs`.
 *
 * @see docs/data.md
 */
final class InventoryWriteOffListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $InventoryWriteOffNumber = null,
        public ?CompletionStatus $Status = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $Location = null,
        #[DateTime]
        public ?string $Date = null,
        public ?string $Notes = null,
    ) {
    }
}
