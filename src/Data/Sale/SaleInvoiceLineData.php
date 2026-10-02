<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

/**
 * Sale Invoice Line Model, also used by credit notes: the shared line fields of `AbstractSaleLineData` and
 * the revenue `Account`.
 *
 * @see docs/data.md
 */
final class SaleInvoiceLineData extends AbstractSaleLineData
{
    public ?string $Account = null;
}
