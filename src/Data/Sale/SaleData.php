<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;

/**
 * Sale, the response of `sale` GET, POST, PUT and DELETE.
 *
 * @see docs/data.md
 */
final class SaleData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<SaleFulfilmentData>|Optional $Fulfilments
     * @param list<SaleInvoiceData>|Optional $Invoices
     * @param list<SaleCreditNoteData>|Optional $CreditNotes
     * @param list<AttachmentLineData>|Optional $Attachments
     * @param list<InventoryMovementLineData>|Optional $InventoryMovements
     * @param list<SaleTransactionLineData>|Optional $Transactions
     */
    public function __construct(
        public string|Optional $ID,
        public string|Optional $Customer,
        public string|Optional $CustomerID,
        public string|Optional $Contact,
        public string|Optional $Phone,
        public string|Optional $Email,
        public string|Optional $DefaultAccount,
        public bool|Optional $SkipQuote,
        public AddressData|Optional $BillingAddress,
        public SaleShippingAddressData|Optional $ShippingAddress,
        public string|Optional $ShippingNotes,
        public string|Optional $BaseCurrency,
        public string|Optional $CustomerCurrency,
        public string|Optional $TaxRule,
        public string|Optional $TaxCalculation,
        public string|Optional $Terms,
        public string|Optional $PriceTier,
        public string|Optional $ShipBy,
        public string|Optional $Location,
        public string|Optional $SaleOrderDate,
        public string|Optional $LastModifiedOn,
        public string|Optional $Note,
        public string|Optional $CustomerReference,
        public float|Optional $COGSAmount,
        public string|Optional $Status,
        public string|Optional $CombinedPickingStatus,
        public string|Optional $CombinedPackingStatus,
        public string|Optional $CombinedShippingStatus,
        public string|Optional $FulFilmentStatus,
        public string|Optional $CombinedInvoiceStatus,
        public string|Optional $CombinedPaymentStatus,
        public string|Optional $CombinedTrackingNumbers,
        public string|Optional $Carrier,
        public float|Optional $CurrencyRate,
        public string|Optional $SalesRepresentative,
        public string|Optional $Type,
        public string|Optional|null $SourceChannel,
        public string|Optional|null $ExternalID,
        public bool|Optional $ServiceOnly,
        public SaleQuoteData|Optional $Quote,
        public SaleOrderData|Optional $Order,
        #[DataCollectionOf(SaleFulfilmentData::class)]
        public array|Optional $Fulfilments,
        #[DataCollectionOf(SaleInvoiceData::class)]
        public array|Optional $Invoices,
        #[DataCollectionOf(SaleCreditNoteData::class)]
        public array|Optional $CreditNotes,
        public SaleManualJournalData|Optional $ManualJournals,
        public AdditionalAttributeData|Optional $AdditionalAttributes,
        #[DataCollectionOf(AttachmentLineData::class)]
        public array|Optional $Attachments,
        #[DataCollectionOf(InventoryMovementLineData::class)]
        public array|Optional $InventoryMovements,
        #[DataCollectionOf(SaleTransactionLineData::class)]
        public array|Optional $Transactions,
    ) {
    }
}
