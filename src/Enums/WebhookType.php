<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The event a webhook listens for.
 *
 * @see docs/data.md
 */
enum WebhookType: string
{
    case SaleCreated = 'Sale/Created';
    case SaleQuoteAuthorised = 'Sale/QuoteAuthorised';
    case SaleOrderAuthorised = 'Sale/OrderAuthorised';
    case SaleVoided = 'Sale/Voided';
    case SaleBackordered = 'Sale/Backordered';
    case SaleShipmentAuthorised = 'Sale/ShipmentAuthorised';
    case SaleInvoiceAuthorised = 'Sale/InvoiceAuthorised';
    case SalePickAuthorised = 'Sale/PickAuthorised';
    case SalePackAuthorised = 'Sale/PackAuthorised';
    case SaleCreditNoteAuthorised = 'Sale/CreditNoteAuthorised';
    case SaleUndo = 'Sale/Undo';
    case SalePartialPaymentReceived = 'Sale/PartialPaymentReceived';
    case SaleFullPaymentReceived = 'Sale/FullPaymentReceived';
    case SaleAttachmentAdded = 'Sale/AttachmentAdded';
    case SaleAdditionalAttributesChanged = 'Sale/AdditionalAttributesChanged';
    case SaleShipmentTrackingNumberChanged = 'Sale/ShipmentTrackingNumberChanged';
    case PurchaseOrderAuthorised = 'Purchase/OrderAuthorised';
    case PurchaseInvoiceAuthorised = 'Purchase/InvoiceAuthorised';
    case PurchaseStockReceivedAuthorised = 'Purchase/StockReceivedAuthorised';
    case PurchaseCreditNoteAuthorised = 'Purchase/CreditNoteAuthorised';
    case PurchaseUpdated = 'Purchase/Updated';
    case CustomerUpdated = 'Customer/Updated';
    case SupplierUpdated = 'Supplier/Updated';
    case ProductUpdated = 'Product/Updated';
    case StockAvailableStockLevelChanged = 'Stock/AvailableStockLevelChanged';
    case LeadUpdated = 'Lead/Updated';
    case LeadConverted = 'Lead/Converted';
    case OpportunityAuthorized = 'Opportunity/Authorized';
    case OpportunityAttachmentAdded = 'Opportunity/AttachmentAdded';
    case OpportunityVoided = 'Opportunity/Voided';
    case OpportunityConverted = 'Opportunity/Converted';
    case TaskOverdue = 'Task/Overdue';
}
