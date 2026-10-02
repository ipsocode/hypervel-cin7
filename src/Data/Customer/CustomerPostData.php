<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Ipsocode\Cin7\Enums\RecordStatus;

/**
 * The body of `customer` POST: the Customer table with the `Status` POST requires, and no `ID`,
 * which Cin7 assigns. The PUT body is `CustomerPutData`.
 *
 * @see docs/data.md
 */
final class CustomerPostData extends AbstractCustomerData
{
    public function __construct(
        string $Name,
        string $Currency,
        string $PaymentTerm,
        string $AccountReceivable,
        string $RevenueAccount,
        string $TaxRule,
        public RecordStatus $Status,
    ) {
        parent::__construct($Name, $Currency, $PaymentTerm, $AccountReceivable, $RevenueAccount, $TaxRule);
    }
}
