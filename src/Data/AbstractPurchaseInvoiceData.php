<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * The fields the Purchase Invoice Model, the Available Fields for Purchase Invoice table and the
 * Advanced purchase invoice partial model share: a purchase's `Invoice`, the response of
 * `purchase/invoice` and the body of its POST, and an advanced purchase's invoices, as
 * `advanced-purchase/invoice` answers them and its POST takes one. Each is a final child that
 * adds its own fields; the children span the purchase and advanced purchase families, so this
 * parent is in `src/Data/` itself.
 *
 * Every invoice needs its `InvoiceDate`, `InvoiceDueDate`, `Status` and `Lines`, so each child
 * passes them to this constructor; `AdditionalCharges`, the `InvoiceNumber` and the totals, which
 * POST does not require, are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseInvoiceData extends Data
{
    public ?string $InvoiceNumber = null;

    /**
     * @var null|list<PurchaseInvoiceAdditionalChargeData>
     */
    #[DataCollectionOf(PurchaseInvoiceAdditionalChargeData::class)]
    public ?array $AdditionalCharges = null;

    public ?float $TotalBeforeTax = null;

    public ?float $Tax = null;

    public ?float $Total = null;

    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     */
    public function __construct(
        #[DateTime]
        public string $InvoiceDate,
        #[DateTime]
        public string $InvoiceDueDate,
        public InvoiceStatus $Status,
        #[DataCollectionOf(PurchaseInvoiceLineData::class)]
        public array $Lines,
    ) {
    }
}
