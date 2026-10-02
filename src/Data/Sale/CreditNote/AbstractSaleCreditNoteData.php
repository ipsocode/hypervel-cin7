<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleFulfilmentPickPackLineData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * The optional fields every sale credit note model shares: the Sale Credit Note Model a Sale
 * embeds, the Sale Credit Note Invoice Partial Model of `sale/creditnote`, and its POST body. The
 * fields one of them requires stay in that child's constructor; a field declared here is set
 * through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleCreditNoteData extends Data
{
    public ?string $Memo = null;

    public ?float $CreditNoteConversionRate = null;

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

    /**
     * @var null|list<SaleFulfilmentPickPackLineData>
     */
    #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
    public ?array $Restock = null;
}
