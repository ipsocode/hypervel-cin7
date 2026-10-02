<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTaskList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;

/**
 * Money Task List, one entry of `MoneyTasks`.
 *
 * @see docs/data.md
 */
final class MoneyTaskListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[DateTime]
        public ?string $Date = null,
        public ?MoneyTaskType $TaskType = null,
        public ?CompletionStatus $Status = null,
        public ?string $SupplierCustomerName = null,
        #[Uuid]
        public ?string $SupplierID = null,
        #[Uuid]
        public ?string $CustomerID = null,
        public ?string $Reference = null,
        public ?float $TotalAmount = null,
    ) {
    }
}
