<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\ProductFamily;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\CostingMethod;

/**
 * The body of `productFamily` PUT: the Product Family table with the `ID` of the family to change,
 * which PUT requires. The POST body is `ProductFamilyPostData`.
 *
 * @see docs/data.md
 */
final class ProductFamilyPutData extends AbstractProductFamilyData
{
    public function __construct(
        string $SKU,
        string $Name,
        string $Category,
        CostingMethod $CostingMethod,
        string $DefaultLocation,
        string $UOM,
        string $Option1Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($SKU, $Name, $Category, $CostingMethod, $DefaultLocation, $UOM, $Option1Name);
    }
}
