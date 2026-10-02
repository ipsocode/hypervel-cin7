<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Fulfilment Pick Pack Model, a fulfilment's `Pick` or `Pack`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPackData extends Data
{
    /**
     * @param list<SaleFulfilmentPickPackLineData>|Optional $Lines
     */
    public function __construct(
        public string|Optional $Status,
        #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
        public array|Optional $Lines,
    ) {
    }
}
