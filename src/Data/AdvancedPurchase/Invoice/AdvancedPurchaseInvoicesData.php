<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Available Fields for Purchase Invoice of `advanced-purchase/invoice`, the `{PurchaseID, Invoices}`
 * envelope every action of the path answers with: the purchase's invoices, each an
 * `AdvancedPurchasePartialInvoiceData`. The table requires both fields.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseInvoicesData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<AdvancedPurchasePartialInvoiceData> $Invoices
     */
    public function __construct(
        #[Uuid]
        public string $PurchaseID,
        #[DataCollectionOf(AdvancedPurchasePartialInvoiceData::class)]
        public array $Invoices,
    ) {
    }
}
