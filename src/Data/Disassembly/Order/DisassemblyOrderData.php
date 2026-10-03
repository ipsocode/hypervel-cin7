<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\DisassemblyStatus;

/**
 * Disassembly Order, the body and response of `disassembly/order`: the order's `OrderLines` and
 * `OrderServiceLines`. POST takes `WORK IN PROGRESS` or `COMPLETED` as its `Status`.
 * `CompletionDate` and `OrderServiceLines` are in the examples, not the table.
 *
 * @see docs/data.md
 */
final class DisassemblyOrderData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<DisassemblyOrderLineData> $OrderLines
     * @param null|list<DisassemblyOrderServiceLineData> $OrderServiceLines
     */
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[In(DisassemblyStatus::WorkInProgress, DisassemblyStatus::Completed)]
        public ?DisassemblyStatus $Status = null,
        #[DateTime]
        public ?string $CompletionDate = null,
        #[DataCollectionOf(DisassemblyOrderLineData::class)]
        public ?array $OrderLines = null,
        #[DataCollectionOf(DisassemblyOrderServiceLineData::class)]
        public ?array $OrderServiceLines = null,
    ) {
    }
}
