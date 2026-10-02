<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoodsList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;

/**
 * Finished Goods List, one entry of `FinishedGoods`.
 *
 * @see docs/data.md
 */
final class FinishedGoodsListData extends Data implements WithResponse
{
    use HasResponse;

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
        public ?string $CustomField1 = null,
        public ?string $CustomField2 = null,
        public ?string $CustomField3 = null,
        public ?string $CustomField4 = null,
        public ?string $CustomField5 = null,
        public ?string $CustomField6 = null,
        public ?string $CustomField7 = null,
        public ?string $CustomField8 = null,
        public ?string $CustomField9 = null,
        public ?string $CustomField10 = null,
    ) {
    }
}
