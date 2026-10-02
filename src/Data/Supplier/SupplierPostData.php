<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Supplier;

/**
 * The body of `supplier` POST: the Supplier table without the `ID` Cin7 assigns. The PUT body is
 * `SupplierPutData`.
 *
 * @see docs/data.md
 */
final class SupplierPostData extends AbstractSupplierData
{
    public function __construct(
        string $Name,
        string $Currency,
        string $PaymentTerm,
        string $AccountPayable,
        string $TaxRule,
    ) {
        parent::__construct($Name, $Currency, $PaymentTerm, $AccountPayable, $TaxRule);
    }
}
