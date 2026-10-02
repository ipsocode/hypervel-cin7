<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Ipsocode\Cin7\Resources\AdvancedPurchase\CreditNoteResource as AdvancedPurchaseCreditNoteResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\InvoiceResource as AdvancedPurchaseInvoiceResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\ManualJournalResource as AdvancedPurchaseManualJournalResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PaymentResource as AdvancedPurchasePaymentResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PutAwayResource as AdvancedPurchasePutAwayResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\StockResource as AdvancedPurchaseStockResource;
use Ipsocode\Cin7\Resources\AdvancedPurchaseResource;
use Ipsocode\Cin7\Resources\BankTransferResource;
use Ipsocode\Cin7\Resources\CustomerResource;
use Ipsocode\Cin7\Resources\JournalResource;
use Ipsocode\Cin7\Resources\Me\AddressesResource;
use Ipsocode\Cin7\Resources\Me\ContactsResource;
use Ipsocode\Cin7\Resources\MeResource;
use Ipsocode\Cin7\Resources\MoneyTaskListResource;
use Ipsocode\Cin7\Resources\MoneyTaskResource;
use Ipsocode\Cin7\Resources\Product\AttachmentsResource as ProductAttachmentsResource;
use Ipsocode\Cin7\Resources\Product\MarkupPricesResource;
use Ipsocode\Cin7\Resources\ProductResource;
use Ipsocode\Cin7\Resources\Purchase\AttachmentResource as PurchaseAttachmentResource;
use Ipsocode\Cin7\Resources\Purchase\CreditNoteResource as PurchaseCreditNoteResource;
use Ipsocode\Cin7\Resources\Purchase\InvoiceResource as PurchaseInvoiceResource;
use Ipsocode\Cin7\Resources\Purchase\ManualJournalResource as PurchaseManualJournalResource;
use Ipsocode\Cin7\Resources\Purchase\OrderResource as PurchaseOrderResource;
use Ipsocode\Cin7\Resources\Purchase\PaymentResource as PurchasePaymentResource;
use Ipsocode\Cin7\Resources\Purchase\StockResource as PurchaseStockResource;
use Ipsocode\Cin7\Resources\PurchaseCreditNoteListResource;
use Ipsocode\Cin7\Resources\PurchaseListResource;
use Ipsocode\Cin7\Resources\PurchaseResource;
use Ipsocode\Cin7\Resources\Ref\Account\BankResource;
use Ipsocode\Cin7\Resources\Ref\AccountResource;
use Ipsocode\Cin7\Resources\Ref\BrandResource;
use Ipsocode\Cin7\Resources\Ref\CategoryResource;
use Ipsocode\Cin7\Resources\Ref\Customer\CreditsResource;
use Ipsocode\Cin7\Resources\Ref\CustomerResource as RefCustomerResource;
use Ipsocode\Cin7\Resources\Ref\FixedAssetTypeResource;
use Ipsocode\Cin7\Resources\Ref\PaymentTermResource;
use Ipsocode\Cin7\Resources\Ref\PriceTierResource;
use Ipsocode\Cin7\Resources\Ref\ProductAvailabilityResource;
use Ipsocode\Cin7\Resources\Ref\Supplier\DepositsResource;
use Ipsocode\Cin7\Resources\Ref\SupplierResource as RefSupplierResource;
use Ipsocode\Cin7\Resources\Ref\TaxResource;
use Ipsocode\Cin7\Resources\Ref\UnitResource;
use Ipsocode\Cin7\Resources\RefResource;
use Ipsocode\Cin7\Resources\Sale\AttachmentResource;
use Ipsocode\Cin7\Resources\Sale\CreditNoteResource;
use Ipsocode\Cin7\Resources\Sale\Fulfilment\PackResource;
use Ipsocode\Cin7\Resources\Sale\Fulfilment\PickResource;
use Ipsocode\Cin7\Resources\Sale\Fulfilment\ShipResource;
use Ipsocode\Cin7\Resources\Sale\FulfilmentResource;
use Ipsocode\Cin7\Resources\Sale\InvoiceResource;
use Ipsocode\Cin7\Resources\Sale\ManualJournalResource;
use Ipsocode\Cin7\Resources\Sale\OrderResource;
use Ipsocode\Cin7\Resources\Sale\PaymentResource;
use Ipsocode\Cin7\Resources\Sale\QuoteResource;
use Ipsocode\Cin7\Resources\SaleCreditNoteListResource;
use Ipsocode\Cin7\Resources\SaleListResource;
use Ipsocode\Cin7\Resources\SaleResource;
use Ipsocode\Cin7\Resources\SupplierResource;
use Ipsocode\Cin7\Resources\TransactionsResource;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * Every accessor on the connector returns its resource class, fresh on each call so the
 * connector singleton stays coroutine-safe.
 *
 * @see docs/resources.md
 */
class ConnectorResourcesTest extends TestCase
{
    public function testCustomerReturnsACustomerResource(): void
    {
        $this->assertInstanceOf(CustomerResource::class, $this->connector()->customer());
    }

    public function testCustomerReturnsAFreshInstanceEveryCall(): void
    {
        $connector = $this->connector();

        $this->assertNotSame($connector->customer(), $connector->customer());
    }

    public function testMoneyTaskReturnsAFreshMoneyTaskResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(MoneyTaskResource::class, $connector->moneyTask());
        $this->assertNotSame($connector->moneyTask(), $connector->moneyTask());
    }

    public function testProductReturnsAProductResource(): void
    {
        $this->assertInstanceOf(ProductResource::class, $this->connector()->product());
    }

    public function testProductReturnsItsAttachmentsAndMarkupPrices(): void
    {
        $product = $this->connector()->product();

        $this->assertInstanceOf(ProductAttachmentsResource::class, $product->attachments());
        $this->assertNotSame($product->attachments(), $product->attachments());
        $this->assertInstanceOf(MarkupPricesResource::class, $product->markupPrices());
        $this->assertNotSame($product->markupPrices(), $product->markupPrices());
    }

    public function testProductReturnsAFreshInstanceEveryCall(): void
    {
        $connector = $this->connector();

        $this->assertNotSame($connector->product(), $connector->product());
    }

    public function testMoneyTaskListReturnsAFreshMoneyTaskListResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(MoneyTaskListResource::class, $connector->moneyTaskList());
        $this->assertNotSame($connector->moneyTaskList(), $connector->moneyTaskList());
    }

    public function testSaleAndSaleListReturnTheirResources(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(SaleResource::class, $connector->sale());
        $this->assertInstanceOf(SaleListResource::class, $connector->saleList());
        $this->assertNotSame($connector->sale(), $connector->sale());
        $this->assertNotSame($connector->saleList(), $connector->saleList());
    }

    public function testSaleReturnsItsNestedResources(): void
    {
        $sale = $this->connector()->sale();

        $this->assertInstanceOf(QuoteResource::class, $sale->quote());
        $this->assertInstanceOf(OrderResource::class, $sale->order());
        $this->assertInstanceOf(FulfilmentResource::class, $sale->fulfilment());
        $this->assertInstanceOf(InvoiceResource::class, $sale->invoice());
        $this->assertInstanceOf(CreditNoteResource::class, $sale->creditNote());
        $this->assertInstanceOf(PaymentResource::class, $sale->payment());
        $this->assertInstanceOf(ManualJournalResource::class, $sale->manualJournal());
        $this->assertInstanceOf(AttachmentResource::class, $sale->attachment());
        $this->assertNotSame($sale->order(), $sale->order());
    }

    public function testSaleCreditNoteListReturnsAFreshSaleCreditNoteListResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(SaleCreditNoteListResource::class, $connector->saleCreditNoteList());
        $this->assertNotSame($connector->saleCreditNoteList(), $connector->saleCreditNoteList());
    }

    public function testPurchaseReturnsAFreshPurchaseResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(PurchaseResource::class, $connector->purchase());
        $this->assertNotSame($connector->purchase(), $connector->purchase());
    }

    public function testPurchaseReturnsItsNestedResources(): void
    {
        $purchase = $this->connector()->purchase();

        $this->assertInstanceOf(PurchaseOrderResource::class, $purchase->order());
        $this->assertNotSame($purchase->order(), $purchase->order());
        $this->assertInstanceOf(PurchaseStockResource::class, $purchase->stock());
        $this->assertNotSame($purchase->stock(), $purchase->stock());
        $this->assertInstanceOf(PurchaseInvoiceResource::class, $purchase->invoice());
        $this->assertNotSame($purchase->invoice(), $purchase->invoice());
        $this->assertInstanceOf(PurchaseCreditNoteResource::class, $purchase->creditNote());
        $this->assertNotSame($purchase->creditNote(), $purchase->creditNote());
        $this->assertInstanceOf(PurchasePaymentResource::class, $purchase->payment());
        $this->assertNotSame($purchase->payment(), $purchase->payment());
        $this->assertInstanceOf(PurchaseManualJournalResource::class, $purchase->manualJournal());
        $this->assertNotSame($purchase->manualJournal(), $purchase->manualJournal());
        $this->assertInstanceOf(PurchaseAttachmentResource::class, $purchase->attachment());
        $this->assertNotSame($purchase->attachment(), $purchase->attachment());
    }

    public function testPurchaseListAndPurchaseCreditNoteListReturnFreshResources(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(PurchaseListResource::class, $connector->purchaseList());
        $this->assertInstanceOf(PurchaseCreditNoteListResource::class, $connector->purchaseCreditNoteList());
        $this->assertNotSame($connector->purchaseList(), $connector->purchaseList());
        $this->assertNotSame($connector->purchaseCreditNoteList(), $connector->purchaseCreditNoteList());
    }

    public function testAdvancedPurchaseReturnsAFreshAdvancedPurchaseResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(AdvancedPurchaseResource::class, $connector->advancedPurchase());
        $this->assertNotSame($connector->advancedPurchase(), $connector->advancedPurchase());
    }

    public function testAdvancedPurchaseReturnsItsNestedResources(): void
    {
        $advancedPurchase = $this->connector()->advancedPurchase();

        $this->assertInstanceOf(AdvancedPurchaseStockResource::class, $advancedPurchase->stock());
        $this->assertNotSame($advancedPurchase->stock(), $advancedPurchase->stock());
        $this->assertInstanceOf(AdvancedPurchasePutAwayResource::class, $advancedPurchase->putAway());
        $this->assertNotSame($advancedPurchase->putAway(), $advancedPurchase->putAway());
        $this->assertInstanceOf(AdvancedPurchaseInvoiceResource::class, $advancedPurchase->invoice());
        $this->assertNotSame($advancedPurchase->invoice(), $advancedPurchase->invoice());
        $this->assertInstanceOf(AdvancedPurchaseCreditNoteResource::class, $advancedPurchase->creditNote());
        $this->assertNotSame($advancedPurchase->creditNote(), $advancedPurchase->creditNote());
        $this->assertInstanceOf(AdvancedPurchasePaymentResource::class, $advancedPurchase->payment());
        $this->assertNotSame($advancedPurchase->payment(), $advancedPurchase->payment());
        $this->assertInstanceOf(AdvancedPurchaseManualJournalResource::class, $advancedPurchase->manualJournal());
        $this->assertNotSame($advancedPurchase->manualJournal(), $advancedPurchase->manualJournal());
    }

    public function testSupplierReturnsAFreshSupplierResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(SupplierResource::class, $connector->supplier());
        $this->assertNotSame($connector->supplier(), $connector->supplier());
    }

    public function testBankTransferJournalAndTransactionsReturnFreshResources(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(BankTransferResource::class, $connector->bankTransfer());
        $this->assertNotSame($connector->bankTransfer(), $connector->bankTransfer());
        $this->assertInstanceOf(JournalResource::class, $connector->journal());
        $this->assertInstanceOf(TransactionsResource::class, $connector->transactions());
        $this->assertNotSame($connector->journal(), $connector->journal());
        $this->assertNotSame($connector->transactions(), $connector->transactions());
    }

    public function testMeReturnsAFreshMeResource(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(MeResource::class, $connector->me());
        $this->assertNotSame($connector->me(), $connector->me());
    }

    public function testMeReturnsItsNestedResources(): void
    {
        $me = $this->connector()->me();

        $this->assertInstanceOf(AddressesResource::class, $me->addresses());
        $this->assertInstanceOf(ContactsResource::class, $me->contacts());
        $this->assertNotSame($me->addresses(), $me->addresses());
        $this->assertNotSame($me->contacts(), $me->contacts());
    }

    public function testAFulfilmentReturnsItsPickPackAndShip(): void
    {
        $fulfilment = $this->connector()->sale()->fulfilment();

        $this->assertInstanceOf(PickResource::class, $fulfilment->pick());
        $this->assertInstanceOf(PackResource::class, $fulfilment->pack());
        $this->assertInstanceOf(ShipResource::class, $fulfilment->ship());
        $this->assertNotSame($fulfilment->pick(), $fulfilment->pick());
    }

    public function testRefReturnsARefResourceWithItsGroupings(): void
    {
        $ref = $this->connector()->ref();

        $this->assertInstanceOf(RefResource::class, $ref);
        $this->assertInstanceOf(TaxResource::class, $ref->tax());
        $this->assertInstanceOf(RefCustomerResource::class, $ref->customer());
        $this->assertInstanceOf(CreditsResource::class, $ref->customer()->credits());
        $this->assertInstanceOf(RefSupplierResource::class, $ref->supplier());
        $this->assertInstanceOf(DepositsResource::class, $ref->supplier()->deposits());
        $this->assertInstanceOf(AccountResource::class, $ref->account());
        $this->assertInstanceOf(ProductAvailabilityResource::class, $ref->productAvailability());
        $this->assertInstanceOf(PriceTierResource::class, $ref->priceTier());
        $this->assertInstanceOf(UnitResource::class, $ref->unit());
        $this->assertInstanceOf(CategoryResource::class, $ref->category());
        $this->assertInstanceOf(BrandResource::class, $ref->brand());
        $this->assertInstanceOf(BankResource::class, $ref->account()->bank());
        $this->assertInstanceOf(FixedAssetTypeResource::class, $ref->fixedAssetType());
        $this->assertInstanceOf(PaymentTermResource::class, $ref->paymentTerm());
    }

    public function testRefReturnsAFreshInstanceEveryCall(): void
    {
        $connector = $this->connector();

        $this->assertNotSame($connector->ref(), $connector->ref());
        $this->assertNotSame($connector->ref()->customer(), $connector->ref()->customer());
        $this->assertNotSame($connector->ref()->supplier(), $connector->ref()->supplier());
        $this->assertNotSame($connector->ref()->account(), $connector->ref()->account());
        $this->assertNotSame($connector->ref()->productAvailability(), $connector->ref()->productAvailability());
        $this->assertNotSame($connector->ref()->priceTier(), $connector->ref()->priceTier());
        $this->assertNotSame($connector->ref()->unit(), $connector->ref()->unit());
        $this->assertNotSame($connector->ref()->category(), $connector->ref()->category());
        $this->assertNotSame($connector->ref()->brand(), $connector->ref()->brand());
        $this->assertNotSame($connector->ref()->account()->bank(), $connector->ref()->account()->bank());
        $this->assertNotSame($connector->ref()->fixedAssetType(), $connector->ref()->fixedAssetType());
        $this->assertNotSame($connector->ref()->paymentTerm(), $connector->ref()->paymentTerm());
    }
}
