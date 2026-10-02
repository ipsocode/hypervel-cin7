<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Concerns\HasAdditionalAttributes;
use Ipsocode\Cin7\Data\ProductPriceData;

/**
 * The fields of the Customer table: the response of `customer` and the body of its POST and PUT.
 * Each is a final child that adds its own fields.
 *
 * Every customer needs its `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`,
 * `RevenueAccount` and `TaxRule`, so each child passes them to this constructor; the optional
 * fields declared here are set through `from()`. `AdditionalAttribute#` is ten wire keys,
 * `AdditionalAttribute1` to `AdditionalAttribute10`. The table types `TaxNumber` as `Int`, but
 * every example has a string or `null`, so it is a nullable string.
 *
 * @see docs/data.md
 */
abstract class AbstractCustomerData extends Data
{
    use HasAdditionalAttributes;

    #[Max(256)]
    public ?string $DisplayName = null;

    public ?string $PriceTier = null;

    public ?string $Carrier = null;

    public ?string $SalesRepresentative = null;

    public ?string $Location = null;

    public ?int $Discount = null;

    #[Max(2000)]
    public ?string $Comments = null;

    public ?string $TaxNumber = null;

    public ?int $CreditLimit = null;

    public ?string $Tags = null;

    public ?string $AttributeSet = null;

    public ?bool $IsOnCreditHold = null;

    public ?bool $IsLegalEntity = null;

    #[Uuid]
    public ?string $CustomerParentID = null;

    #[Max(256)]
    public ?string $CustomerParentName = null;

    public ?bool $IsBillParent = null;

    /**
     * @var null|list<ProductPriceData>
     */
    #[DataCollectionOf(ProductPriceData::class)]
    public ?array $ProductPrices = null;

    /**
     * @var null|list<CustomerAddressData>
     */
    #[DataCollectionOf(CustomerAddressData::class)]
    public ?array $Addresses = null;

    /**
     * @var null|list<CustomerContactData>
     */
    #[DataCollectionOf(CustomerContactData::class)]
    public ?array $Contacts = null;

    public function __construct(
        #[Max(256)]
        public string $Name,
        public string $Currency,
        public string $PaymentTerm,
        public string $AccountReceivable,
        public string $RevenueAccount,
        public string $TaxRule,
    ) {
    }
}
