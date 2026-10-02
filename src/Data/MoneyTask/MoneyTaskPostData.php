<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTask;

use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;

/**
 * The body of `moneyOperation` POST: the Money Task table without the `TaskID` Cin7 assigns. The
 * PUT body is `MoneyTaskPutData`.
 *
 * @see docs/data.md
 */
final class MoneyTaskPostData extends AbstractMoneyTaskData
{
    public function __construct(
        MoneyTaskType $TaskType,
        CompletionStatus $Status,
        string $BankAccount,
        string $Date,
    ) {
        parent::__construct($TaskType, $Status, $BankAccount, $Date);
    }
}
