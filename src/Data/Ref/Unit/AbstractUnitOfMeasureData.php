<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Unit;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields of the unit of measure table: the response of `ref/unit` and the body of its POST and PUT. Each
 * is a final child that adds its `ID`, or none. Every unit of measure needs its `Name`, so each child
 * passes it to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractUnitOfMeasureData extends Data
{
    public function __construct(
        #[Max(50)]
        public string $Name,
    ) {
    }
}
