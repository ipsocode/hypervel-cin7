<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\ProductSuppliers;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Product\ProductSupplierData;

/**
 * The `ProductSuppliers` of a product: the response of `product-suppliers` GET and the body of its
 * POST and PUT, which are the same. The reference has no table of its own for it: each is a
 * `ProductSupplierData`, which here names its product by `ProductID` or `ProductSKU`.
 *
 * @see docs/data.md
 */
final class ProductSuppliersData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<ProductSupplierData> $ProductSuppliers
     */
    public function __construct(
        #[DataCollectionOf(ProductSupplierData::class)]
        public array $ProductSuppliers,
    ) {
    }
}
