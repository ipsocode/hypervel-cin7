<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CostingMethod;
use Ipsocode\Cin7\Enums\ProductStatus;
use Ipsocode\Cin7\Enums\ProductType;

/**
 * Product, the entries of `Products` in every `product` response: the Product table with its
 * `ID` and `Type` and the read-only `AverageCost`, `LastModifiedOn` and `BOMType`. The bodies of
 * POST and PUT are `ProductPostData` and `ProductPutData`.
 *
 * @see docs/data.md
 */
final class ProductData extends AbstractProductData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $SKU,
        string $Name,
        string $Category,
        CostingMethod $CostingMethod,
        string $UOM,
        ProductStatus $Status,
        #[Uuid]
        public ?string $ID = null,
        public ?ProductType $Type = null,
        public ?float $AverageCost = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
        public ?string $BOMType = null,
    ) {
        parent::__construct($SKU, $Name, $Category, $CostingMethod, $UOM, $Status);
    }
}
