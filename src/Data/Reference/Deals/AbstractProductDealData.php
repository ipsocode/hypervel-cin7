<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Data;

/**
 * The fields of the Product Deal table: the response of every `reference/deals` action and the body
 * of its POST and PUT. Each is a final child that adds the fields it requires.
 *
 * Every deal needs its `Name`, so each child passes it to this constructor; the optional fields
 * declared here are set through `from()`. `CouponCodes` is a string, as the table says.
 *
 * @see docs/data.md
 */
abstract class AbstractProductDealData extends Data
{
    #[Date]
    public ?string $DateFrom = null;

    #[Date]
    public ?string $DateTo = null;

    public ?string $CustomersGroup = null;

    public ?string $CouponCodes = null;

    /**
     * @var null|list<ProductDealCustomerData>
     */
    #[DataCollectionOf(ProductDealCustomerData::class)]
    public ?array $DealCustomers = null;

    /**
     * @var null|list<ProductDealTagData>
     */
    #[DataCollectionOf(ProductDealTagData::class)]
    public ?array $DealCustomerTags = null;

    /**
     * @var null|list<ProductDealDiscountData>
     */
    #[DataCollectionOf(ProductDealDiscountData::class)]
    public ?array $DealDiscounts = null;

    public function __construct(
        public string $Name,
    ) {
    }
}
