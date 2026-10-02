<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTake;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * IDName Model, an `ID` and a `Name`: a category, brand or bin a stock take filters its products by.
 *
 * @see docs/data.md
 */
final class IdNameData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $Name = null,
    ) {
    }
}
