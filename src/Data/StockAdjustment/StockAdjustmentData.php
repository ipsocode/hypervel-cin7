<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockAdjustment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\ExistingStockLineData;
use Ipsocode\Cin7\Data\Other\NewStockLineData;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * Stock Adjustment, the response of every `stockadjustment` action: the table with its `TaskID`,
 * the lines it changed (`ExistingStockLines`, in non-zero stock, and `NewStockLines`, in zero
 * stock) and the transactions they created. The bodies of POST and PUT are
 * `StockAdjustmentPostData` and `StockAdjustmentPutData`.
 *
 * @see docs/data.md
 */
final class StockAdjustmentData extends AbstractStockAdjustmentData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ExistingStockLineData> $ExistingStockLines
     * @param null|list<NewStockLineData> $NewStockLines
     * @param null|list<TransactionStockLineData> $Transactions
     */
    public function __construct(
        string $EffectiveDate,
        CompletionStatus $Status,
        #[Uuid]
        public ?string $TaskID = null,
        #[DataCollectionOf(ExistingStockLineData::class)]
        public ?array $ExistingStockLines = null,
        #[DataCollectionOf(NewStockLineData::class)]
        public ?array $NewStockLines = null,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public ?array $Transactions = null,
    ) {
        parent::__construct($EffectiveDate, $Status);
    }
}
