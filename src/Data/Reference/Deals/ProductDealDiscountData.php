<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DiscountRuleType;

/**
 * Product Deal Discount, one entry of a deal's `DealDiscounts`: a discount rule, by `DiscountID` or
 * `DiscountName`, and the brands, categories, tags and products it applies to. `DiscountType` is the
 * rule's type. The table lists `BuyMore` twice; it is one field.
 *
 * @see docs/data.md
 */
final class ProductDealDiscountData extends Data
{
    /**
     * @param null|list<ProductDealDiscountBrandData> $DealDiscountBrands
     * @param null|list<ProductDealDiscountCategoryData> $DealDiscountCategories
     * @param null|list<ProductDealDiscountTagData> $DealDiscountTags
     * @param null|list<ProductDealDiscountProductData> $DealDiscountProducts
     */
    public function __construct(
        public DiscountRuleType $DiscountType,
        public bool $IsOrderLevel,
        #[Uuid]
        public ?string $ID = null,
        #[Uuid]
        public ?string $DiscountID = null,
        public ?string $DiscountName = null,
        public ?int $Sequence = null,
        public ?string $BuyType = null,
        public ?float $BuyValue = null,
        public ?string $BuyValueType = null,
        public ?bool $BuyMore = null,
        public ?string $GetType = null,
        public ?float $GetValue = null,
        public ?string $GetValueType = null,
        #[DataCollectionOf(ProductDealDiscountBrandData::class)]
        public ?array $DealDiscountBrands = null,
        #[DataCollectionOf(ProductDealDiscountCategoryData::class)]
        public ?array $DealDiscountCategories = null,
        #[DataCollectionOf(ProductDealDiscountTagData::class)]
        public ?array $DealDiscountTags = null,
        #[DataCollectionOf(ProductDealDiscountProductData::class)]
        public ?array $DealDiscountProducts = null,
    ) {
    }
}
