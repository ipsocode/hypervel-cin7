<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTakeList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\StockTakeStatus;

/**
 * Stock Take List, one entry of `StockAdjustmentList`, the key the reference's example uses. Its
 * filters (`Tags`, `PickZones`, `StockLocators`, `Categories`, `Brands`, `Bins`) are comma
 * delimited strings here, where the stock take has lists.
 *
 * @see docs/data.md
 */
final class StockTakeListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[DateTime]
        public ?string $EffectiveDate = null,
        public ?string $StocktakeNumber = null,
        public ?StockTakeStatus $Status = null,
        public ?string $Account = null,
        public ?string $LocationID = null,
        public ?string $Location = null,
        public ?string $Tags = null,
        public ?string $PickZones = null,
        public ?string $StockLocators = null,
        public ?string $Categories = null,
        public ?string $Brands = null,
        public ?string $Bins = null,
        public ?string $Reference = null,
        public ?string $CreatedDate = null,
        public ?string $LastUpdatedDate = null,
        public ?string $LastUpdatedBy = null,
    ) {
    }
}
