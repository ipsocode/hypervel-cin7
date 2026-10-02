<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;

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
        public ?string $BaseCurrency = null,
        public ?string $CustomerCurrency = null,
        public ?string $TaxCalculation = null,
        public ?float $COGSAmount = null,
        public ?string $Status = null,
        public ?string $CombinedPickingStatus = null,
        public ?string $CombinedPackingStatus = null,
        public ?string $CombinedShippingStatus = null,
        public ?string $FulFilmentStatus = null,
        public ?string $CombinedInvoiceStatus = null,
        public ?string $CombinedPaymentStatus = null,
        public ?string $CombinedTrackingNumbers = null,
        public ?string $Type = null,
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
    }
}
