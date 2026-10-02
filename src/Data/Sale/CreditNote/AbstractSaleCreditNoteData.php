<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Sale\SaleFulfilmentPickPackLineData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * The fields every sale credit note model shares: the Sale Credit Note Model a Sale embeds, the
 * Sale Credit Note Invoice Partial Model of `sale/creditnote`, and its POST body. All three tables
 * mark `TaskID`, `Status` and `CreditNoteDate` required, so each child passes them to this
 * constructor; the fields only one child requires stay in that child's constructor, and the
 * optional fields declared here are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleCreditNoteData extends Data
{
    #[Max(1024)]
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

    public function __construct(
        #[Uuid]
        public string $TaskID,
        public string $Status,
        #[DateTime]
        public string $CreditNoteDate,
    ) {
    }
}
