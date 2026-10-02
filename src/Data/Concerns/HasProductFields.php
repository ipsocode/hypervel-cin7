<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Concerns;

use Hypervel\Data\Attributes\Validation\Max;

/**
 * The fields the reference adds to an object that carries a `ProductID`: "All objects that
 * contain ProductID also contain additional fields". The sale line, pick and pack line and
 * inventory movement classes take them from here.
 *
 * @see docs/data.md
 */
trait HasProductFields
{
    public ?float $ProductLength = null;

    public ?float $ProductWidth = null;

    public ?float $ProductHeight = null;

    public ?float $ProductWeight = null;

    #[Max(10)]
    public ?string $WeightUnits = null;

    #[Max(10)]
    public ?string $DimensionsUnits = null;

    public ?string $ProductCustomField1 = null;

    public ?string $ProductCustomField2 = null;

    public ?string $ProductCustomField3 = null;

    public ?string $ProductCustomField4 = null;

    public ?string $ProductCustomField5 = null;

    public ?string $ProductCustomField6 = null;

    public ?string $ProductCustomField7 = null;

    public ?string $ProductCustomField8 = null;

    public ?string $ProductCustomField9 = null;

    public ?string $ProductCustomField10 = null;
}
