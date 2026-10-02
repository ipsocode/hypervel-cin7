<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Pack;

use Hypervel\Data\Attributes\Validation\In;
use Ipsocode\Cin7\Data\Sale\Fulfilment\AbstractSaleFulfilmentPickPackTaskData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `sale/fulfilment/pack` POST, which creates a pack or adds lines to one: the pack,
 * whose `Status` POST takes as `DRAFT` or `AUTHORISED`. The PUT body is `SaleFulfilmentPackData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPackPostData extends AbstractSaleFulfilmentPickPackTaskData
{
    public function __construct(
        string $TaskID,
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
    ) {
        parent::__construct($TaskID);
    }
}
