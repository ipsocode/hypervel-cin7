<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Fulfilment Pick Pack Model, a fulfilment's `Pick` or `Pack`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPackData extends Data
{
    /**
     * @param null|list<SaleFulfilmentPickPackLineData> $Lines
     */
    public function __construct(
        public ?TaskStatus $Status = null,
        #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
