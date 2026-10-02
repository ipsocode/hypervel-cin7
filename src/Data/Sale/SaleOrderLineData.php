<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Order Line Model, a superset of the Sale Quote Line the reference's Sale Order table names (adds `BackorderQuantity` and `DropShip`; `Backorder` appears only in the examples).
 *
 * @see docs/data.md
 */
final class SaleOrderLineData extends Data
{
    public function __construct(
        public string|Optional $ProductID,
        public string|Optional $SKU,
        public string|Optional $Name,
        public float|Optional $Quantity,
        public float|Optional $Price,
        public float|Optional $Discount,
        public float|Optional $Tax,
        public float|Optional $AverageCost,
        public string|Optional $TaxRule,
        public string|Optional $Comment,
        public bool|Optional $DropShip,
        public bool|Optional $Backorder,
        public float|Optional $BackorderQuantity,
        public float|Optional $Total,
        public float|Optional $ProductLength,
        public float|Optional $ProductWidth,
        public float|Optional $ProductHeight,
        public float|Optional $ProductWeight,
        public string|Optional $WeightUnits,
        public string|Optional $DimensionsUnits,
        public string|Optional|null $ProductCustomField1,
        public string|Optional|null $ProductCustomField2,
        public string|Optional|null $ProductCustomField3,
        public string|Optional|null $ProductCustomField4,
        public string|Optional|null $ProductCustomField5,
        public string|Optional|null $ProductCustomField6,
        public string|Optional|null $ProductCustomField7,
        public string|Optional|null $ProductCustomField8,
        public string|Optional|null $ProductCustomField9,
        public string|Optional|null $ProductCustomField10,
    ) {
    }
}
