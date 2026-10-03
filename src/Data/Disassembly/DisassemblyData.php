<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderLineData;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderServiceLineData;
use Ipsocode\Cin7\Data\Other\ErrorData;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\DisassemblyStatus;

/**
 * Disassembly, the response of every `disassembly` action: the task with its pick, order and
 * service lines, its transactions and any `Errors` raised while a POST ran. `CompletionDate` is in
 * the examples, not the table. The POST body is `DisassemblyPostData`.
 *
 * @see docs/data.md
 */
final class DisassemblyData extends AbstractDisassemblyData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<DisassemblyPickLineData> $PickLines
     * @param null|list<DisassemblyOrderLineData> $OrderLines
     * @param null|list<DisassemblyOrderServiceLineData> $OrderServiceLines
     * @param null|list<TransactionStockLineData> $Transactions
     * @param null|list<ErrorData> $Errors
     */
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $DisassemblyNumber = null,
        public ?DisassemblyStatus $Status = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $ProductCode = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $Location = null,
        public ?string $WIPAccount = null,
        public ?float $Quantity = null,
        public ?string $AssemblyInstructionURL = null,
        #[DataCollectionOf(DisassemblyPickLineData::class)]
        public ?array $PickLines = null,
        #[DataCollectionOf(DisassemblyOrderLineData::class)]
        public ?array $OrderLines = null,
        #[DataCollectionOf(DisassemblyOrderServiceLineData::class)]
        public ?array $OrderServiceLines = null,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public ?array $Transactions = null,
        #[DataCollectionOf(ErrorData::class)]
        public ?array $Errors = null,
    ) {
    }
}
