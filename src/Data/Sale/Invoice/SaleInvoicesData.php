<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
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
     * @param null|list<SaleInvoicePartialData> $Invoices
     */
    public function __construct(
        #[Uuid]
        public ?string $SaleID = null,
        #[DataCollectionOf(SaleInvoicePartialData::class)]
        public ?array $Invoices = null,
    ) {
    }
}
