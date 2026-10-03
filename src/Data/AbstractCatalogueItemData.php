<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DropShipMode;

/**
 * The optional fields a product and a product family declare alike: the reference's Product and
 * Product Family tables give them the same type and length. `AbstractProductData` and
 * `AbstractProductFamilyData` extend this class and add their own, and each takes its required
 * fields in its own constructor, because their order is part of each class's signature and
 * `SKU`, `UOM`, `Brand` and `DefaultLocation` differ in length between the two tables.
 *
 * @see docs/data.md
 */
abstract class AbstractCatalogueItemData extends Data
{
    public ?DropShipMode $DropShipMode = null;

    public ?float $MinimumBeforeReorder = null;

    public ?float $ReorderQuantity = null;

    public ?float $PriceTier1 = null;

    public ?float $PriceTier2 = null;

    public ?float $PriceTier3 = null;

    public ?float $PriceTier4 = null;

    public ?float $PriceTier5 = null;

    public ?float $PriceTier6 = null;

    public ?float $PriceTier7 = null;

    public ?float $PriceTier8 = null;

    public ?float $PriceTier9 = null;

    public ?float $PriceTier10 = null;

    #[Max(500)]
    public ?string $ShortDescription = null;

    public ?string $Description = null;

    #[Max(50)]
    public ?string $AttributeSet = null;

    #[Max(128)]
    public ?string $DiscountRule = null;

    #[Max(256)]
    public ?string $Tags = null;

    #[Max(50)]
    public ?string $COGSAccount = null;

    #[Max(50)]
    public ?string $RevenueAccount = null;

    #[Max(50)]
    public ?string $InventoryAccount = null;

    #[Max(50)]
    public ?string $PurchaseTaxRule = null;

    #[Max(50)]
    public ?string $SaleTaxRule = null;

    #[Max(200)]
    public ?string $HSCode = null;

    public ?string $CountryOfOrigin = null;
}
