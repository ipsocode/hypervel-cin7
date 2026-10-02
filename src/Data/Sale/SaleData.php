<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNoteData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoiceData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalData;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuoteData;
use Ipsocode\Cin7\Enums\FulfilmentStatus;
use Ipsocode\Cin7\Enums\PackingStatus;
use Ipsocode\Cin7\Enums\PickingStatus;
use Ipsocode\Cin7\Enums\SalePaymentStatus;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\SaleType;
use Ipsocode\Cin7\Enums\ShippingStatus;
use Ipsocode\Cin7\Enums\TaxCalculation;

/**
 * Sale, the response of `sale` GET, POST, PUT and DELETE.
 *
 * @see docs/data.md
 */
final class SaleData extends AbstractSaleData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<SaleFulfilmentData> $Fulfilments
     * @param null|list<SaleInvoiceData> $Invoices
     * @param null|list<SaleCreditNoteData> $CreditNotes
     * @param null|list<AttachmentLineData> $Attachments
     * @param null|list<InventoryMovementLineData> $InventoryMovements
     * @param null|list<SaleTransactionLineData> $Transactions
     */
    public function __construct(
        string $Location,
        float $CurrencyRate,
        public PickingStatus $CombinedPickingStatus,
        public PackingStatus $CombinedPackingStatus,
        public ShippingStatus $CombinedShippingStatus,
        #[Uuid]
        public ?string $ID = null,
        #[Max(3)]
        public ?string $BaseCurrency = null,
        #[Max(3)]
        public ?string $CustomerCurrency = null,
        public ?TaxCalculation $TaxCalculation = null,
        public ?float $COGSAmount = null,
        public ?SaleStatus $Status = null,
        public ?FulfilmentStatus $FulFilmentStatus = null,
        #[Max(20)]
        public ?string $CombinedInvoiceStatus = null,
        public ?SalePaymentStatus $CombinedPaymentStatus = null,
        #[Max(256)]
        public ?string $CombinedTrackingNumbers = null,
        public ?SaleType $Type = null,
        #[Max(32)]
        public ?string $SourceChannel = null,
        public ?bool $ServiceOnly = null,
        public ?SaleQuoteData $Quote = null,
        public ?SaleOrderData $Order = null,
        #[DataCollectionOf(SaleFulfilmentData::class)]
        public ?array $Fulfilments = null,
        #[DataCollectionOf(SaleInvoiceData::class)]
        public ?array $Invoices = null,
        #[DataCollectionOf(SaleCreditNoteData::class)]
        public ?array $CreditNotes = null,
        public ?SaleManualJournalData $ManualJournals = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(InventoryMovementLineData::class)]
        public ?array $InventoryMovements = null,
        #[DataCollectionOf(SaleTransactionLineData::class)]
        public ?array $Transactions = null,
    ) {
        parent::__construct($Location, $CurrencyRate);
    }
}
