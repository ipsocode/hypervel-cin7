<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Invoice;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractChargeData;

/**
 * Purchase Invoice Additional Charge Model, also used by the purchase credit notes and the
 * advanced purchase's invoices and credit notes: the charge fields of `AbstractChargeData`, a
 * `Reference`, an optional `Total` and the `Account` the table requires.
 *
 * @see docs/data.md
 */
final class PurchaseInvoiceAdditionalChargeData extends AbstractChargeData
{
    #[Max(256)]
    public ?string $Reference = null;

    public ?float $Total = null;

    public function __construct(
        string $Description,
        float $Quantity,
        float $Price,
        float $Tax,
        string $TaxRule,
        #[Max(50)]
        public string $Account,
    ) {
        parent::__construct($Description, $Quantity, $Price, $Tax, $TaxRule);
    }
}
