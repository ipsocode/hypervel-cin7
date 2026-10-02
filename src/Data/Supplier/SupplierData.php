<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Supplier;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Supplier, the entries of `SupplierList` in every `supplier` response: the Supplier table with
 * its required `ID` and `LastModifiedOn`, the date Cin7 stamps on a change. The bodies of POST
 * and PUT are `SupplierPostData` and `SupplierPutData`.
 *
 * @see docs/data.md
 */
final class SupplierData extends AbstractSupplierData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        string $Currency,
        string $PaymentTerm,
        string $AccountPayable,
        string $TaxRule,
        #[Uuid]
        public string $ID,
        #[DateTime]
        public ?string $LastModifiedOn = null,
    ) {
        parent::__construct($Name, $Currency, $PaymentTerm, $AccountPayable, $TaxRule);
    }
}
