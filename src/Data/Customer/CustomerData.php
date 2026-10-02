<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\ProductPriceData;

/**
 * Customer, the body of `customer` POST and PUT and the entries of `CustomerList` in every `customer` response.
 *
 * `AdditionalAttribute#` is ten wire keys, `AdditionalAttribute1` to `AdditionalAttribute10`. The reference's
 * table types `TaxNumber` as `Int`, but every example has a string or `null`, so it is a nullable string.
 * `ChildCustomers` is only present in responses.
 *
 * @see docs/data.md
 */
final class CustomerData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<ProductPriceData>|Optional $ProductPrices
     * @param list<CustomerAddressData>|Optional $Addresses
     * @param list<CustomerContactData>|Optional $Contacts
     * @param list<ChildCustomerData>|Optional $ChildCustomers
     */
    public function __construct(
        public string|Optional $ID,
        public string|Optional $Name,
        public string|Optional|null $DisplayName,
        public string|Optional $Status,
        public string|Optional $Currency,
        public string|Optional $PaymentTerm,
        public string|Optional $AccountReceivable,
        public string|Optional $RevenueAccount,
        public string|Optional $TaxRule,
        public string|Optional|null $PriceTier,
        public string|Optional|null $Carrier,
        public string|Optional|null $SalesRepresentative,
        public string|Optional|null $Location,
        public float|Optional $Discount,
        public string|Optional|null $Comments,
        public string|Optional|null $TaxNumber,
        public float|Optional $CreditLimit,
        public string|Optional|null $Tags,
        public string|Optional|null $AttributeSet,
        public string|Optional|null $AdditionalAttribute1,
        public string|Optional|null $AdditionalAttribute2,
        public string|Optional|null $AdditionalAttribute3,
        public string|Optional|null $AdditionalAttribute4,
        public string|Optional|null $AdditionalAttribute5,
        public string|Optional|null $AdditionalAttribute6,
        public string|Optional|null $AdditionalAttribute7,
        public string|Optional|null $AdditionalAttribute8,
        public string|Optional|null $AdditionalAttribute9,
        public string|Optional|null $AdditionalAttribute10,
        public string|Optional $LastModifiedOn,
        public bool|Optional $IsOnCreditHold,
        public bool|Optional $IsLegalEntity,
        public string|Optional|null $CustomerParentID,
        public string|Optional|null $CustomerParentName,
        public bool|Optional $IsBillParent,
        #[DataCollectionOf(ProductPriceData::class)]
        public array|Optional $ProductPrices,
        #[DataCollectionOf(CustomerAddressData::class)]
        public array|Optional $Addresses,
        #[DataCollectionOf(CustomerContactData::class)]
        public array|Optional $Contacts,
        #[DataCollectionOf(ChildCustomerData::class)]
        public array|Optional $ChildCustomers,
    ) {
    }
}
