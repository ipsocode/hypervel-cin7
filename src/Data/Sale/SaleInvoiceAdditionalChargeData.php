<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

/**
 * Sale Invoice Additional Charge Model, also used by credit notes: the shared charge fields of
 * `AbstractSaleChargeData` and the revenue `Account`.
 *
 * @see docs/data.md
 */
final class SaleInvoiceAdditionalChargeData extends AbstractSaleChargeData
{
    public ?string $Account = null;
}
