<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoodsList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasCustomFields;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;

/**
 * Finished Goods List, one entry of `FinishedGoods`.
 *
 * @see docs/data.md
 */
final class FinishedGoodsListData extends Data implements WithResponse
{
    use HasResponse;
    use HasCustomFields;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $AssemblyNumber = null,
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        public ?float $Quantity = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $Location = null,
        #[DateTime]
        public ?string $Date = null,
        public ?FinishedGoodsStatus $Status = null,
        public ?float $UnitCost = null,
        public ?string $Notes = null,
    ) {
    }
}
