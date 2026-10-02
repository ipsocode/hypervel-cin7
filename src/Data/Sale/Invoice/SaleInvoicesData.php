<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale Invoices, the `{SaleID, Invoices}` envelope every `sale/invoice` action answers with.
 *
 * @see docs/data.md
 */
final class SaleInvoicesData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<SaleInvoicePartialData>|Optional $Invoices
     */
    public function __construct(
        public string|Optional $SaleID,
        #[DataCollectionOf(SaleInvoicePartialData::class)]
        public array|Optional $Invoices,
    ) {
    }
}
