<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransferList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CostDistributionType;
use Ipsocode\Cin7\Enums\StockTransferStatus;

/**
 * Stock Transfer List, one entry of `StockTransferList`.
 *
 * @see docs/data.md
 */
final class StockTransferListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[Uuid]
        public ?string $From = null,
        public ?string $FromLocation = null,
        #[Uuid]
        public ?string $To = null,
        public ?string $ToLocation = null,
        public ?StockTransferStatus $Status = null,
        public ?string $Number = null,
        #[DateTime]
        public ?string $CompletionDate = null,
        public ?CostDistributionType $CostDistributionType = null,
        public ?string $InTransitAccount = null,
        #[DateTime]
        public ?string $DepartureDate = null,
        public ?string $Reference = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
    ) {
    }
}
