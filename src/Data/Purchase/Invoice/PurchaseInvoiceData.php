<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractPurchaseInvoiceData;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * Purchase Invoice Model, a purchase's `Invoice`, and the Available Fields for Purchase Invoice
 * table, the response of `purchase/invoice`. One name, so one class carrying the union: the
 * table's `TaskID`, `CombineAdditionalCharges`, `InvoiceTotalAmount` and `InvoiceTotalTaxAmount`
 * and the model's `Payments` and `Paid` are optional, as only one of the two has them. The POST
 * body is `PurchaseInvoicePostData`.
 *
 * @see docs/data.md
 */
final class PurchaseInvoiceData extends AbstractPurchaseInvoiceData implements WithResponse
{
    use HasResponse;

    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param null|list<SalePaymentLineData> $Payments
     */
    public function __construct(
        string $InvoiceDate,
        string $InvoiceDueDate,
        InvoiceStatus $Status,
        array $Lines,
        #[Uuid]
        public ?string $TaskID = null,
        public ?bool $CombineAdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Payments = null,
        public ?float $Paid = null,
        public ?float $InvoiceTotalAmount = null,
        public ?float $InvoiceTotalTaxAmount = null,
    ) {
        parent::__construct($InvoiceDate, $InvoiceDueDate, $Status, $Lines);
    }
}
