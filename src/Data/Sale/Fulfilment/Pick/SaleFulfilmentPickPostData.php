<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Pick;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Ipsocode\Cin7\Data\Sale\Fulfilment\AbstractSaleFulfilmentPickPackTaskData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `sale/fulfilment/pick` POST: the pick, whose `Status` POST takes as `DRAFT` or
 * `AUTHORISED`. Sending `AutoPickMode` (`AUTOPICK`) instead of a `Status` picks the task
 * automatically and authorises it, which the reference documents in prose only. The PUT body is
 * `SaleFulfilmentPickPutData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPostData extends AbstractSaleFulfilmentPickPackTaskData
{
    public function __construct(
        string $TaskID,
        #[RequiredWithout('AutoPickMode')]
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public ?TaskStatus $Status = null,
        public ?string $AutoPickMode = null,
    ) {
        parent::__construct($TaskID);
    }
}
