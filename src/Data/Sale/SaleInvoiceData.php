<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Ipsocode\Cin7\Data\Sale\Invoice\AbstractSaleInvoiceData;

/**
 * Sale Invoice Model.
 *
 * @see docs/data.md
 */
final class SaleInvoiceData extends AbstractSaleInvoiceData
{
    /**
     * @param null|list<SalePaymentLineData> $Payments
     */
    public function __construct(
        public ?string $TaskID = null,
        public ?string $InvoiceNumber = null,
        public ?string $Status = null,
        public ?string $InvoiceDate = null,
        public ?string $InvoiceDueDate = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Payments = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
        public ?float $Paid = null,
    ) {
    }
}
