<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Concerns;

use Hypervel\Data\Attributes\Validation\Max;

/**
 * The fields the reference adds to an object that carries a `ProductID`: "All objects that
 * contain ProductID also contain additional fields". The product line classes (every child of
 * `AbstractLineData`: the sale quote, order and invoice lines and the purchase order and invoice
 * lines), the pick and pack line, the purchase stock and unstock lines, the advanced purchase
 * stock and put away lines and the inventory movement line take them from here.
 *
 * `WeightUnits` and `DimensionsUnits` take an abbreviation from the reference's Dimension Unit
 * Available Values (`g`, `kg`, `cm`, `in`, …), but stay strings rather than the `WeightUnit` and
 * `DimensionUnit` enums: the sale examples send `""`, outside the list.
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
