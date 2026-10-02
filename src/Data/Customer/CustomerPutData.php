<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\RecordStatus;

/**
 * The body of `customer` PUT: the Customer table with the `ID` of the customer to change. The
 * POST body is `CustomerPostData`.
 *
 * @see docs/data.md
 */
final class CustomerPutData extends AbstractCustomerData
{
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
    ) {
        parent::__construct($Name, $Currency, $PaymentTerm, $AccountReceivable, $RevenueAccount, $TaxRule);
    }
}
