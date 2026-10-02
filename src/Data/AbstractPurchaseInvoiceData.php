<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * The fields the Purchase Invoice Model, the Available Fields for Purchase Invoice table, the
 * Advanced purchase invoice partial model and the Advanced Purchase Invoice Model share: a
 * purchase's `Invoice`, the response of `purchase/invoice` and the body of its POST, an advanced
 * purchase's invoices, as `advanced-purchase/invoice` answers them and its POST takes one, and an
 * advanced purchase's `Invoice`. Each is a final child that adds its own fields; the children span
 * the purchase and advanced purchase families, so this parent is in `src/Data/` itself.
 *
 * Every invoice needs its `Status` and `Lines`, so each child passes them to this constructor;
 * `AdditionalCharges`, the `InvoiceNumber` and the totals, which POST does not require, are set
 * through `from()`. Every table requires `InvoiceDate` and `InvoiceDueDate` too, but the
 * `purchase` examples embed an invoice with a `null` `InvoiceDueDate`, and the `advanced-purchase`
 * examples one with both `null`, so each child declares them: the write bodies and the advanced
 * purchase's partial invoices require both, `PurchaseInvoiceData` requires `InvoiceDate` and
 * leaves `InvoiceDueDate` optional, and `AdvancedPurchaseInvoiceData` leaves both optional.
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
        public InvoiceStatus $Status,
        #[DataCollectionOf(PurchaseInvoiceLineData::class)]
        public array $Lines,
    ) {
    }
}
