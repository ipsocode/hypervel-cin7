<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Ipsocode\Cin7\Enums\CostingMethod;
use Ipsocode\Cin7\Enums\ProductStatus;
use Ipsocode\Cin7\Enums\ProductType;

/**
 * The body of `product` POST: the Product table with the `Type` only POST sets, and no `ID`,
 * which POST ignores. The PUT body is `ProductPutData`.
 *
 * @see docs/data.md
 */
final class ProductPostData extends AbstractProductData
{
    public function __construct(
        string $SKU,
        string $Name,
        string $Category,
        CostingMethod $CostingMethod,
        string $UOM,
        ProductStatus $Status,
        public ProductType $Type,
    ) {
        parent::__construct($SKU, $Name, $Category, $CostingMethod, $UOM, $Status);
    }
}
