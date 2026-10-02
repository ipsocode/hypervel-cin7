<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * Advanced Purchase Invoice Model, one invoice of an advanced purchase's `Invoice`: the invoice
 * fields of `AbstractPurchaseInvoiceData`, with the `TaskID` the model requires, `Payments`
 * (Purchase Payment Line Models) and `Paid`. Its lines and additional charges are the Purchase
 * Invoice Line and Additional Charge Models.
 *
 * The model requires `InvoiceDate` and `InvoiceDueDate`, but the `advanced-purchase` POST, PUT and
 * DELETE examples embed an invoice that is `NOT AVAILABLE` with both `null`, so both are optional
 * here. Those examples also send `InvoicingAndReceivingNumber`, which the model does not list; it
 * is modelled, optional.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseInvoiceData extends AbstractPurchaseInvoiceData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param null|list<PurchasePaymentLineData> $Payments
     */
    public function __construct(
        InvoiceStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
        #[DateTime]
        public ?string $InvoiceDate = null,
        #[DateTime]
        public ?string $InvoiceDueDate = null,
        public ?int $InvoicingAndReceivingNumber = null,
        #[DataCollectionOf(PurchasePaymentLineData::class)]
        public ?array $Payments = null,
        public ?float $Paid = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
