<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Concerns\HasAdditionalAttributes;
use Ipsocode\Cin7\Data\ProductPriceData;
use Ipsocode\Cin7\Enums\RecordStatus;

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
    use HasAdditionalAttributes;
    use HasResponse;

    /**
     * @param null|list<ProductPriceData> $ProductPrices
     * @param null|list<CustomerAddressData> $Addresses
     * @param null|list<CustomerContactData> $Contacts
     * @param null|list<ChildCustomerData> $ChildCustomers
     */
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Max(256)]
        public ?string $Name = null,
        #[Max(256)]
        public ?string $DisplayName = null,
        public ?RecordStatus $Status = null,
        public ?string $Currency = null,
        public ?string $PaymentTerm = null,
        public ?string $AccountReceivable = null,
        public ?string $RevenueAccount = null,
        public ?string $TaxRule = null,
        public ?string $PriceTier = null,
        public ?string $Carrier = null,
        public ?string $SalesRepresentative = null,
        public ?string $Location = null,
        public ?int $Discount = null,
        #[Max(2000)]
        public ?string $Comments = null,
        public ?string $TaxNumber = null,
        public ?int $CreditLimit = null,
        public ?string $Tags = null,
        public ?string $AttributeSet = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
        public ?bool $IsOnCreditHold = null,
        public ?bool $IsLegalEntity = null,
        #[Uuid]
        public ?string $CustomerParentID = null,
        #[Max(256)]
        public ?string $CustomerParentName = null,
        public ?bool $IsBillParent = null,
        #[DataCollectionOf(ProductPriceData::class)]
        public ?array $ProductPrices = null,
        #[DataCollectionOf(CustomerAddressData::class)]
        public ?array $Addresses = null,
        #[DataCollectionOf(CustomerContactData::class)]
        public ?array $Contacts = null,
        #[DataCollectionOf(ChildCustomerData::class)]
        public ?array $ChildCustomers = null,
    ) {
    }
}
