<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Sale\SalePaymentLineData;

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
        string $TaskID,
        public ?string $InvoiceNumber = null,
        public ?string $Status = null,
        #[DateTime]
        public ?string $InvoiceDate = null,
        #[DateTime]
        public ?string $InvoiceDueDate = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Payments = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
        public ?float $Paid = null,
    ) {
        parent::__construct($TaskID);
    }
}
