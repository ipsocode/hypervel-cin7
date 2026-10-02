<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Pick;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Ipsocode\Cin7\Data\Sale\Fulfilment\AbstractSaleFulfilmentPickPackTaskData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `sale/fulfilment/pick` PUT: the pick, replacing an unauthorised one. Sending
 * `AutoPickMode` (`AUTOPICK`) instead of a `Status` picks the task automatically and authorises
 * it, which the reference documents in prose only. The POST body is `SaleFulfilmentPickPostData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPutData extends AbstractSaleFulfilmentPickPackTaskData
{
    public function __construct(
        string $TaskID,
        #[RequiredWithout('AutoPickMode')]
        public ?TaskStatus $Status = null,
        public ?string $AutoPickMode = null,
    ) {
        parent::__construct($TaskID);
    }
}
