<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockAdjustmentList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * Stock Adjustment List, one entry of `StockAdjustmentList`.
 *
 * @see docs/data.md
 */
final class StockAdjustmentListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[DateTime]
        public ?string $EffectiveDate = null,
        public ?string $StocktakeNumber = null,
        public ?CompletionStatus $Status = null,
        public ?string $Account = null,
        public ?string $Reference = null,
        public ?string $Comment = null,
    ) {
    }
}
