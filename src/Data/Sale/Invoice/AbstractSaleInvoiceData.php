<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields every sale invoice model shares: the Sale Invoice Model a Sale embeds, the Sale
 * Invoice Partial Model of `sale/invoice`, and its POST and PUT bodies. All four require
 * `TaskID`, so each child passes it to this constructor; the fields only some children require
 * (PUT needs just `SaleID` and `TaskID`) stay in their constructors, and the optional fields
 * declared here are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleInvoiceData extends Data
{
    #[Max(1024)]
    public ?string $Memo = null;

    public ?float $CurrencyConversionRate = null;

    #[Max(256)]
    public ?string $BillingAddressLine1 = null;

    #[Max(256)]
    public ?string $BillingAddressLine2 = null;

    public ?string $LinkedFulfillmentNumber = null;

    /**
     * @var null|list<SaleInvoiceLineData>
     */
    #[DataCollectionOf(SaleInvoiceLineData::class)]
    public ?array $Lines = null;

    /**
     * @var null|list<SaleInvoiceAdditionalChargeData>
     */
    #[DataCollectionOf(SaleInvoiceAdditionalChargeData::class)]
    public ?array $AdditionalCharges = null;

    public function __construct(
        #[Uuid]
        public string $TaskID,
    ) {
    }
}
