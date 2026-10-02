<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTaskList;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Money Task List, one entry of `MoneyTasks`.
 *
 * @see docs/data.md
 */
final class MoneyTaskListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public string|Optional $TaskID,
        public string|Optional $Date,
        public string|Optional $TaskType,
        public string|Optional $Status,
        public string|Optional $SupplierCustomerName,
        public string|Optional|null $SupplierID,
        public string|Optional|null $CustomerID,
        public string|Optional $Reference,
        public float|Optional $TotalAmount,
    ) {
    }
}
