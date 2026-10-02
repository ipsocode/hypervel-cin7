<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\CostingMethod;
use Ipsocode\Cin7\Enums\ProductStatus;

/**
 * The body of `product` PUT: the Product table with the `ID` PUT requires, and no `Type`, which
 * is read-only for PUT. The POST body is `ProductPostData`.
 *
 * @see docs/data.md
 */
final class ProductPutData extends AbstractProductData
{
    public function __construct(
        string $SKU,
        string $Name,
        string $Category,
        CostingMethod $CostingMethod,
        string $UOM,
        ProductStatus $Status,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($SKU, $Name, $Category, $CostingMethod, $UOM, $Status);
    }
}
