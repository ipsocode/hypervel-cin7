<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Unit;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/unit` PUT: the table with the `ID` of the unit of measure to change, which PUT requires.
 * The POST body is `UnitOfMeasurePostData`.
 *
 * @see docs/data.md
 */
final class UnitOfMeasurePutData extends AbstractUnitOfMeasureData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
