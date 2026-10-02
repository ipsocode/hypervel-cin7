<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOff;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\ErrorData;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * Inventory Write-Off, the response of every `inventoryWriteOff` action: the table with its
 * `TaskID`, its auto-generated `InventoryWriteOffNumber`, the `Transactions` it created and the
 * `Errors` of a POST or PUT that created the task despite them. The bodies of POST and PUT are
 * `InventoryWriteOffPostData` and `InventoryWriteOffPutData`.
 *
 * @see docs/data.md
 */
final class InventoryWriteOffData extends AbstractInventoryWriteOffData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<TransactionStockLineData> $Transactions
     * @param null|list<ErrorData> $Errors
     */
    public function __construct(
        CompletionStatus $Status,
        string $Account,
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $InventoryWriteOffNumber = null,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public ?array $Transactions = null,
        #[DataCollectionOf(ErrorData::class)]
        public ?array $Errors = null,
    ) {
        parent::__construct($Status, $Account);
    }
}
