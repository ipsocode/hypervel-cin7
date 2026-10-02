<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Enums\RecordStatus;

/**
 * Customer, the entries of `CustomerList` in every `customer` response: the Customer table with
 * its required `ID`, the read-only `LastModifiedOn`, and `ChildCustomers`, which only responses
 * carry. The bodies of POST and PUT are `CustomerPostData` and `CustomerPutData`.
 *
 * @see docs/data.md
 */
final class CustomerData extends AbstractCustomerData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ChildCustomerData> $ChildCustomers
     */
    public function __construct(
        string $Name,
        string $Currency,
        string $PaymentTerm,
        string $AccountReceivable,
        string $RevenueAccount,
        string $TaxRule,
        #[Uuid]
        public string $ID,
        public ?RecordStatus $Status = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
        #[DataCollectionOf(ChildCustomerData::class)]
        public ?array $ChildCustomers = null,
    ) {
        parent::__construct($Name, $Currency, $PaymentTerm, $AccountReceivable, $RevenueAccount, $TaxRule);
    }
}
