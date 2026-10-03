<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

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
        public InvoiceStatus $Status,
        #[DateTime]
        public string $InvoiceDate,
        #[DateTime]
        public string $InvoiceDueDate,
        public ?string $InvoiceNumber = null,
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
