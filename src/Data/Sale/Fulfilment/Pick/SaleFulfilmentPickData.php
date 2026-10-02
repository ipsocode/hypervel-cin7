<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Pick;

use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Sale\Fulfilment\AbstractSaleFulfilmentPickPackTaskData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Fulfilment Pick, the response of every `sale/fulfilment/pick` action. The bodies of POST
 * and PUT are `SaleFulfilmentPickPostData` and `SaleFulfilmentPickPutData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickData extends AbstractSaleFulfilmentPickPackTaskData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $TaskID,
        public TaskStatus $Status,
    ) {
        parent::__construct($TaskID);
    }
}
