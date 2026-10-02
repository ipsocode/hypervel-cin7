<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Supplier;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Concerns\HasAdditionalAttributes;
use Ipsocode\Cin7\Data\Other\CustomerAddressData;
use Ipsocode\Cin7\Data\Other\CustomerContactData;
use Ipsocode\Cin7\Enums\RecordStatus;

/**
 * The fields of the Supplier table: the response of `supplier` and the body of its POST and PUT.
 * Each is a final child that adds its own fields.
 *
 * Every supplier needs its `Name`, `Currency`, `PaymentTerm`, `AccountPayable` and `TaxRule`, so
 * each child passes them to this constructor; the optional fields declared here are set through
 * `from()`. `Status` is marked `Yes*` with no condition, so it is optional. `AdditionalAttribute#`
 * is ten wire keys, `AdditionalAttribute1` to `AdditionalAttribute10`. The table types
 * `TaxNumber` as `Int`, but every example has a string or `null`, so it is a nullable string.
 * `Addresses` and `Contacts` are the Supplier Address and Contact Models, which the customer
 * shares.
 *
 * @see docs/data.md
 */
abstract class AbstractSupplierData extends Data
{
    use HasAdditionalAttributes;

    public ?RecordStatus $Status = null;

    public ?int $Discount = null;

    #[Max(256)]
    public ?string $Comments = null;

    public ?string $TaxNumber = null;

    public ?string $AttributeSet = null;

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
        public string $AccountPayable,
        public string $TaxRule,
    ) {
    }
}
