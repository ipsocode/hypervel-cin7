<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Supplier;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `supplier` PUT: the Supplier table with the `ID` of the supplier to change. The
 * POST body is `SupplierPostData`.
 *
 * @see docs/data.md
 */
final class SupplierPutData extends AbstractSupplierData
{
    public function __construct(
        string $Name,
        string $Currency,
        string $PaymentTerm,
        string $AccountPayable,
        string $TaxRule,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name, $Currency, $PaymentTerm, $AccountPayable, $TaxRule);
    }
}
