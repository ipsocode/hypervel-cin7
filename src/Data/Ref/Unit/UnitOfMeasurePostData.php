<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Unit;

/**
 * The body of `ref/unit` POST: the table without the `ID` Cin7 ignores on POST. The PUT body is
 * `UnitOfMeasurePutData`.
 *
 * @see docs/data.md
 */
final class UnitOfMeasurePostData extends AbstractUnitOfMeasureData
{
}
