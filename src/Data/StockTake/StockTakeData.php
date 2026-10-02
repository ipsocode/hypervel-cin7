<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTake;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\StockTakeStatus;

/**
 * Stock Take, the response of every `stocktake` action: the table with its `TaskID`, its
 * auto-generated `StocktakeNumber` and the `Transactions` its stock changes created. The bodies of
 * POST and PUT are `StockTakePostData` and `StockTakePutData`.
 *
 * @see docs/data.md
 */
final class StockTakeData extends AbstractStockTakeData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<TransactionStockLineData> $Transactions
     */
    public function __construct(
        string $EffectiveDate,
        string $Account,
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $StocktakeNumber = null,
        public ?StockTakeStatus $Status = null,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public ?array $Transactions = null,
    ) {
        parent::__construct($EffectiveDate, $Account);
    }
}
