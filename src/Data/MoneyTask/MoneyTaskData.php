<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTask;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;

/**
 * Money Task, the response of every `moneyOperation` action: the Money Task table with its
 * `TaskID`, which only PUT requires. The bodies of POST and PUT are `MoneyTaskPostData` and
 * `MoneyTaskPutData`.
 *
 * @see docs/data.md
 */
final class MoneyTaskData extends AbstractMoneyTaskData implements WithResponse
{
    use HasResponse;

    public function __construct(
        MoneyTaskType $TaskType,
        CompletionStatus $Status,
        string $BankAccount,
        string $Date,
        #[Uuid]
        public ?string $TaskID = null,
    ) {
        parent::__construct($TaskType, $Status, $BankAccount, $Date);
    }
}
