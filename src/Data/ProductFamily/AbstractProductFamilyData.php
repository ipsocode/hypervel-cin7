<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\ProductFamily;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\AbstractCatalogueItemData;
use Ipsocode\Cin7\Enums\CostingMethod;

/**
 * The fields of the Product Family table: the response of `productFamily` and the body of its POST
 * and PUT. Each is a final child that adds its `ID`, or none.
 *
 * Every family needs its `SKU`, `Name`, `Category`, `CostingMethod`, `DefaultLocation`, `UOM` and
 * `Option1Name`, so each child passes them to this constructor; the optional fields declared here
 * are set through `from()`. `Products` are the products in the family: a PUT adds or updates the
 * ones it lists and never deletes one. A product's `SKU` and `Name` are ignored in a POST or PUT,
 * so the requests leave them out of the body.
 * The fields it shares with a product are in `AbstractCatalogueItemData`.
 *
 * @see docs/data.md
 */
abstract class AbstractProductFamilyData extends AbstractCatalogueItemData
{
    /**
     * @var null|list<ProductFamilyProductLineData>
     */
    #[DataCollectionOf(ProductFamilyProductLineData::class)]
    public ?array $Products = null;

    #[Max(256)]
    public ?string $Brand = null;

    #[Max(50)]
    public ?string $Option2Name = null;

    #[Max(50)]
    public ?string $Option3Name = null;

    public function __construct(
        #[Max(45)]
        public string $SKU,
        #[Max(256)]
        public string $Name,
        #[Max(256)]
        public string $Category,
        public CostingMethod $CostingMethod,
        #[Max(256)]
        public string $DefaultLocation,
        public string $UOM,
        #[Max(50)]
        public string $Option1Name,
    ) {
    }
}
