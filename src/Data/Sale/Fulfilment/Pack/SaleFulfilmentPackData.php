<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Pack;

use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Sale\Fulfilment\AbstractSaleFulfilmentPickPackTaskData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Fulfilment Pack, the response of every `sale/fulfilment/pack` action and the body of its
 * PUT, which replaces an unauthorised pack. The POST body is `SaleFulfilmentPackPostData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPackData extends AbstractSaleFulfilmentPickPackTaskData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $TaskID,
        public TaskStatus $Status,
    ) {
        parent::__construct($TaskID);
    }
}
