<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\FinishedGoods\Order\FinishedGoodsOrderLineData;
use Ipsocode\Cin7\Data\FinishedGoods\Pick\FinishedGoodsPickLineData;
use Ipsocode\Cin7\Data\Other\ErrorData;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;

/**
 * Finished Goods, the response of every `finishedGoods` action: the task with its order and pick
 * lines, its transactions and any `Errors` raised while a POST ran. The bodies of POST and PUT are
 * `FinishedGoodsPostData` and `FinishedGoodsPutData`.
 *
 * @see docs/data.md
 */
final class FinishedGoodsData extends AbstractFinishedGoodsData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<FinishedGoodsOrderLineData> $OrderLines
     * @param null|list<FinishedGoodsPickLineData> $PickLines
     * @param null|list<TransactionStockLineData> $Transactions
     * @param null|list<ErrorData> $Errors
     */
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $AssemblyNumber = null,
        public ?FinishedGoodsStatus $Status = null,
        public ?string $WIPAccount = null,
        public ?string $Account = null,
        public ?float $Quantity = null,
        public ?string $AssemblyInstructionURL = null,
        #[DateTime]
        public ?string $CompletionDate = null,
        #[DataCollectionOf(FinishedGoodsOrderLineData::class)]
        public ?array $OrderLines = null,
        #[DataCollectionOf(FinishedGoodsPickLineData::class)]
        public ?array $PickLines = null,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public ?array $Transactions = null,
        #[DataCollectionOf(ErrorData::class)]
        public ?array $Errors = null,
    ) {
    }
}
