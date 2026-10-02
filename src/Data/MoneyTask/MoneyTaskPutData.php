<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTask;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;

/**
 * The body of `moneyOperation` PUT: the Money Task table with the `TaskID` PUT requires. The POST
 * body is `MoneyTaskPostData`.
 *
 * @see docs/data.md
 */
final class MoneyTaskPutData extends AbstractMoneyTaskData
{
    public function __construct(
        MoneyTaskType $TaskType,
        CompletionStatus $Status,
        string $BankAccount,
        string $Date,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($TaskType, $Status, $BankAccount, $Date);
    }
}
