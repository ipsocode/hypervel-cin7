<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * The optional fields every sale invoice model shares: the Sale Invoice Model a Sale embeds, the
 * Sale Invoice Partial Model of `sale/invoice`, and its POST and PUT bodies. The fields one of
 * them requires stay in that child's constructor; a field declared here is set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleInvoiceData extends Data
{
    public ?string $Memo = null;

    public ?float $CurrencyConversionRate = null;

    public ?string $BillingAddressLine1 = null;

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
}
