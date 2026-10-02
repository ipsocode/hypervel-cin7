<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Closure;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Hypervel\Validation\ValidationException;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePutData;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchasePartialCreditNotePostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchasePartialInvoicePostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchasePartialManualJournalPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPutData;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityPostData;
use Ipsocode\Cin7\Data\Customer\CustomerPostData;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffPostData;
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPricesData;
use Ipsocode\Cin7\Data\Product\ProductPostData;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentPostData;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNotePostData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoicePostData;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalPostData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderPostData;
use Ipsocode\Cin7\Data\Purchase\PurchasePostData;
use Ipsocode\Cin7\Data\Purchase\PurchasePutData;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockPostData;
use Ipsocode\Cin7\Data\Ref\Account\AccountPostData;
use Ipsocode\Cin7\Data\Ref\Account\AccountPutData;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRulePutData;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentPostData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPutData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPutData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePutData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalPostData;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuotePostData;
use Ipsocode\Cin7\Data\Sale\SalePostData;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentPostData;
use Ipsocode\Cin7\Data\StockTake\StockTakePostData;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferPostData;
use Ipsocode\Cin7\Data\Webhooks\WebhookPostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\PostAdvancedPurchaseCreditNote;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\PostAdvancedPurchaseInvoice;
use Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal\PostAdvancedPurchaseManualJournal;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PostAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway\PostAdvancedPurchasePutAway;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\PostAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\PutAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\Crm\Opportunity\PostCrmOpportunity;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\InventoryWriteOff\PostInventoryWriteOff;
use Ipsocode\Cin7\Requests\Product\MarkupPrices\PutProductMarkupPrices;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Purchase\Attachment\PostPurchaseAttachment;
use Ipsocode\Cin7\Requests\Purchase\CreditNote\PostPurchaseCreditNote;
use Ipsocode\Cin7\Requests\Purchase\Invoice\PostPurchaseInvoice;
use Ipsocode\Cin7\Requests\Purchase\ManualJournal\PostPurchaseManualJournal;
use Ipsocode\Cin7\Requests\Purchase\Order\PostPurchaseOrder;
use Ipsocode\Cin7\Requests\Purchase\PostPurchase;
use Ipsocode\Cin7\Requests\Purchase\PutPurchase;
use Ipsocode\Cin7\Requests\Purchase\Stock\PostPurchaseStock;
use Ipsocode\Cin7\Requests\Ref\Account\PostAccount;
use Ipsocode\Cin7\Requests\Ref\Account\PutAccount;
use Ipsocode\Cin7\Requests\Reference\Discount\PutDiscount;
use Ipsocode\Cin7\Requests\Sale\Attachment\PostSaleAttachment;
use Ipsocode\Cin7\Requests\Sale\CreditNote\PostSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\PostSaleFulfilmentPack;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PostSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PutSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PostSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PutSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Invoice\PostSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PutSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\ManualJournal\PostSaleManualJournal;
use Ipsocode\Cin7\Requests\Sale\Order\PostSaleOrder;
use Ipsocode\Cin7\Requests\Sale\Payment\PostSalePayment;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\Quote\PostSaleQuote;
use Ipsocode\Cin7\Requests\StockAdjustment\PostStockAdjustment;
use Ipsocode\Cin7\Requests\StockTake\PostStockTake;
use Ipsocode\Cin7\Requests\StockTransfer\PostStockTransfer;
use Ipsocode\Cin7\Requests\Webhooks\PostWebhooks;
use Ipsocode\Cin7\Requests\WriteRequest;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Workbench\App\Support\Cin7Payloads;

/**
 * A data object body is checked against its class's rules, the reference's lengths, GUIDs and
 * dates among them, after its nulls and omitted fields are removed and before anything is sent.
 *
 * @see docs/data.md
 */
class BodyValidationTest extends TestCase
{
    /**
     * The fields every product POST requires.
     *
     * @var array<string, string>
     */
    private const array PRODUCT = ['SKU' => 'Bread', 'Name' => 'Baked Bread', 'Category' => 'Other', 'CostingMethod' => 'FIFO', 'UOM' => 'Item', 'Status' => 'Active', 'Type' => 'Stock'];

    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 4, MockResponse::make(Cin7Payloads::salePayment())));
    }

    public function testAValidBodyIsSent(): void
    {
        $this->connector()->send(new PostSalePayment(SalePaymentPostData::from(Cin7Payloads::salePaymentPost())));

        $this->mock->assertSentCount(1);
    }

    /**
     * @param array<string, mixed> $changes
     */
    #[DataProvider('invalidPaymentProvider')]
    public function testAPaymentBreakingARuleIsNotSent(array $changes, string $field): void
    {
        $body = SalePaymentPostData::from(array_replace(Cin7Payloads::salePaymentPost(), $changes));

        try {
            $this->connector()->send(new PostSalePayment($body));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame([$field], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function invalidPaymentProvider(): array
    {
        return [
            'a TaskID that is not a GUID' => [['TaskID' => 'task-1'], 'TaskID'],
            'a DatePaid without a time' => [['DatePaid' => '2017-11-30'], 'DatePaid'],
            'a DatePaid with an offset' => [['DatePaid' => '2017-11-30T00:00:00+10:00'], 'DatePaid'],
            'an impossible DatePaid' => [['DatePaid' => '2017-13-45T00:00:00'], 'DatePaid'],
        ];
    }

    /**
     * Cin7's DateTime is `yyyy-MM-ddTHH:mm:ss`, with an optional fraction of up to seven digits and
     * an optional `Z`; the nil GUID is a GUID.
     */
    #[DataProvider('validDateProvider')]
    public function testCin7DateTimesAndTheNilGuidPass(string $date): void
    {
        $body = SalePaymentPostData::from(['TaskID' => '00000000-0000-0000-0000-000000000000', 'DatePaid' => $date] + Cin7Payloads::salePaymentPost());

        $this->connector()->send(new PostSalePayment($body));

        $this->mock->assertSentCount(1);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDateProvider(): array
    {
        return [
            'seconds' => ['2017-11-30T00:00:00'],
            'milliseconds' => ['2012-11-14T13:28:33.363'],
            'seven fraction digits and Z' => ['2017-11-22T06:58:21.8882229Z'],
        ];
    }

    /**
     * The Length column: a field one character longer than the reference allows fails, at any
     * depth, and one at the limit passes.
     */
    public function testALengthOverTheReferencesLimitFailsAtAnyDepth(): void
    {
        $invoice = Cin7Payloads::saleInvoicePost();
        $invoice['Memo'] = str_repeat('m', 1024);
        $invoice['BillingAddressLine1'] = str_repeat('a', 257);
        $invoice['Lines'][0]['SKU'] = str_repeat('s', 51);

        try {
            $this->connector()->send(new PostSaleInvoice(SaleInvoicePostData::from($invoice)));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['BillingAddressLine1', 'Lines.0.SKU'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * POST takes only `DRAFT` and `AUTHORISED`, though the status enums have more values.
     *
     * @param Closure(): WriteRequest $request
     */
    #[DataProvider('postOnlyStatusProvider')]
    public function testAStatusOutsideThePostValuesIsNotSent(Closure $request): void
    {
        try {
            $this->connector()->send($request());
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Status'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * @return array<string, array{Closure(): WriteRequest}>
     */
    public static function postOnlyStatusProvider(): array
    {
        return [
            'invoice POST' => [fn (): WriteRequest => new PostSaleInvoice(SaleInvoicePostData::from(['Status' => 'VOIDED'] + Cin7Payloads::saleInvoicePost()))],
            'invoice PUT' => [fn (): WriteRequest => new PutSaleInvoice(SaleInvoicePutData::from(['Status' => 'PAID'] + Cin7Payloads::saleInvoicePut()))],
            'credit note POST' => [fn (): WriteRequest => new PostSaleCreditNote(SaleCreditNotePostData::from(['Status' => 'VOIDED'] + Cin7Payloads::saleCreditNotePost()))],
            'order POST' => [fn (): WriteRequest => new PostSaleOrder(SaleOrderData::from(['Status' => 'CLOSED'] + Cin7Payloads::saleOrder()))],
            'pick POST' => [fn (): WriteRequest => new PostSaleFulfilmentPick(SaleFulfilmentPickPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'VOIDED']))],
            'pack POST' => [fn (): WriteRequest => new PostSaleFulfilmentPack(SaleFulfilmentPackPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'NOT AVAILABLE']))],
            'ship POST' => [fn (): WriteRequest => new PostSaleFulfilmentShip(SaleFulfilmentShipPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'VOIDED']))],
            'ship PUT' => [fn (): WriteRequest => new PutSaleFulfilmentShip(SaleFulfilmentShipPutData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'NOT AVAILABLE']))],
            'quote POST' => [fn (): WriteRequest => new PostSaleQuote(SaleQuotePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Status' => 'VOIDED', 'Lines' => []]))],
            'manual journal POST' => [fn (): WriteRequest => new PostSaleManualJournal(SaleManualJournalPostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'NOT AVAILABLE']))],
            'purchase order POST' => [fn (): WriteRequest => new PostPurchaseOrder(PurchaseOrderPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Status' => 'VOIDED', 'Lines' => []]))],
            'purchase stock POST' => [fn (): WriteRequest => new PostPurchaseStock(PurchaseStockPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'NOT AVAILABLE', 'Lines' => []]))],
            'purchase manual journal POST' => [fn (): WriteRequest => new PostPurchaseManualJournal(PurchaseManualJournalPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'VOIDED']))],
            'advanced purchase stock POST' => [fn (): WriteRequest => new PostAdvancedPurchaseStock(AdvancedPurchaseStockPostData::from(['Status' => 'VOIDED'] + Cin7Payloads::load('advanced-purchase/stock', 'post.request')))],
            'advanced purchase stock PUT' => [fn (): WriteRequest => new PutAdvancedPurchaseStock(AdvancedPurchaseStockPutData::from(['Status' => 'NOT AVAILABLE'] + Cin7Payloads::load('advanced-purchase/stock', 'put.request')))],
            'advanced purchase manual journal POST' => [fn (): WriteRequest => new PostAdvancedPurchaseManualJournal(AdvancedPurchasePartialManualJournalPostData::from(['Status' => 'NOT AVAILABLE'] + Cin7Payloads::load('advanced-purchase/manualJournal', 'post.request')))],
            'purchase invoice POST' => [fn (): WriteRequest => new PostPurchaseInvoice(PurchaseInvoicePostData::from(['Status' => 'PAID'] + Cin7Payloads::load('purchase/invoice', 'post.request')))],
            'purchase credit note POST' => [fn (): WriteRequest => new PostPurchaseCreditNote(PurchaseCreditNotePostData::from(['Status' => 'VOIDED'] + Cin7Payloads::load('purchase/creditnote', 'post.request')))],
            'advanced purchase invoice POST' => [fn (): WriteRequest => new PostAdvancedPurchaseInvoice(AdvancedPurchasePartialInvoicePostData::from(['Status' => 'VOIDED'] + Cin7Payloads::load('advanced-purchase/invoice', 'post.request')))],
            'advanced purchase put away POST' => [fn (): WriteRequest => new PostAdvancedPurchasePutAway(AdvancedPurchasePutAwayPostData::from(['Status' => 'VOIDED'] + Cin7Payloads::load('advanced-purchase/put-away', 'post.request')))],
            'advanced purchase credit note POST' => [fn (): WriteRequest => new PostAdvancedPurchaseCreditNote(AdvancedPurchasePartialCreditNotePostData::from(['Status' => 'NOT AVAILABLE'] + Cin7Payloads::load('advanced-purchase/creditnote', 'post.request')))],
        ];
    }

    /**
     * An attachment is sent as base64 `Content` or a `FileDownloadUrl`; a body with neither is
     * not sent.
     */
    public function testAnAttachmentNeedsItsContentOrADownloadUrl(): void
    {
        try {
            $this->connector()->send(new PostSaleAttachment(SaleAttachmentPostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Content'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostSaleAttachment(SaleAttachmentPostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'])));

        $this->mock->assertSentCount(1);
    }

    /**
     * A purchase attachment, too, is sent as base64 `Content` or a `FileDownloadUrl`.
     */
    public function testAPurchaseAttachmentNeedsItsContentOrADownloadUrl(): void
    {
        try {
            $this->connector()->send(new PostPurchaseAttachment(PurchaseAttachmentPostData::from(['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Content'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostPurchaseAttachment(PurchaseAttachmentPostData::from(['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'])));

        $this->mock->assertSentCount(1);
    }

    /**
     * A pick needs its `Status`, unless `AutoPickMode` picks it automatically.
     */
    public function testAPickNeedsAStatusUnlessItIsAutoPicked(): void
    {
        try {
            $this->connector()->send(new PostSaleFulfilmentPick(SaleFulfilmentPickPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Status'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostSaleFulfilmentPick(SaleFulfilmentPickPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK'])));
        $this->connector()->send(new PutSaleFulfilmentPick(SaleFulfilmentPickPutData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A received stock line needs its location, by name or by ID; a body with a line with neither
     * is not sent, and either is enough.
     */
    public function testAReceivedStockLineNeedsItsLocation(): void
    {
        $stock = static fn (array $line): PurchaseStockPostData => PurchaseStockPostData::from([
            'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136',
            'Status' => 'DRAFT',
            'Lines' => [['Date' => '2017-12-08T00:00:00', 'Quantity' => 3, 'SKU' => 'Bread'] + $line],
        ]);

        try {
            $this->connector()->send(new PostPurchaseStock($stock([])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Lines.0.Location', 'Lines.0.LocationID'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostPurchaseStock($stock(['Location' => 'Main Warehouse'])));
        $this->connector()->send(new PostPurchaseStock($stock(['LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A sale needs a customer, by name or by ID; a body with neither is not sent.
     */
    public function testASaleWithoutACustomerIsNotSent(): void
    {
        try {
            $this->connector()->send(new PostSale(SalePostData::from(['Location' => 'Main Warehouse', 'CurrencyRate' => 1])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Customer', 'CustomerID'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    public function testEitherCustomerFieldIsEnoughForASale(): void
    {
        $this->connector()->send(new PostSale(SalePostData::from(['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])));
        $this->connector()->send(new PostSale(SalePostData::from(['CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A put away line needs its location, by name or by ID: a body with a line that has neither is
     * not sent, and either one is enough.
     */
    public function testAPutAwayLineNeedsItsLocationOrLocationId(): void
    {
        $body = ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'DRAFT'];
        $line = ['Date' => '2018-04-20T00:00:00', 'Quantity' => 4, 'SKU' => 'Bread'];

        try {
            $this->connector()->send(new PostAdvancedPurchasePutAway(AdvancedPurchasePutAwayPostData::from($body + ['Lines' => [$line]])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Lines.0.Location', 'Lines.0.LocationID'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostAdvancedPurchasePutAway(AdvancedPurchasePutAwayPostData::from($body + ['Lines' => [$line + ['Location' => 'Main Warehouse']]])));
        $this->connector()->send(new PostAdvancedPurchasePutAway(AdvancedPurchasePutAwayPostData::from($body + ['Lines' => [$line + ['LocationID' => 'ccb7d97b-a638-4b34-833e-4c348b81f40d']]])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A free shipping discount line needs the order value it applies above; a body without it is
     * not sent.
     */
    public function testAFreeShippingDiscountLineNeedsItsOrderExceeds(): void
    {
        $rule = ['ID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Name' => 'Free'];

        try {
            $this->connector()->send(new PutDiscount(ProductDiscountRulePutData::from($rule + ['DiscountLines' => [['DiscountType' => 'FreeShipping']]])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['DiscountLines.0.OrderExceeds'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PutDiscount(ProductDiscountRulePutData::from($rule + ['DiscountLines' => [['DiscountType' => 'FreeShipping', 'OrderExceeds' => 100]]])));

        $this->mock->assertSentCount(1);
    }

    /**
     * A new stock line needs its product, by `ProductID` or `SKU`, and its location, by `LocationID`
     * or `Location`: a body with a line that has neither of either is not sent, and one of each
     * is enough.
     */
    public function testANewStockLineNeedsItsProductAndItsLocation(): void
    {
        $body = ['EffectiveDate' => '2017-12-01T00:00:00', 'Status' => 'DRAFT'];
        $line = ['Quantity' => 1, 'UnitCost' => 2];

        try {
            $this->connector()->send(new PostStockAdjustment(StockAdjustmentPostData::from($body + ['Lines' => [$line]])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['Lines.0.ProductID', 'Lines.0.SKU', 'Lines.0.LocationID', 'Lines.0.Location'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostStockAdjustment(StockAdjustmentPostData::from($body + ['Lines' => [$line + ['SKU' => 'AF308', 'Location' => 'Main Warehouse']]])));
        $this->connector()->send(new PostStockAdjustment(StockAdjustmentPostData::from($body + ['Lines' => [$line + ['ProductID' => 'ccb7d97b-a638-4b34-833e-4c348b81f40d', 'LocationID' => 'cd3ed3bb-673a-4d48-b47b-5f92a973ae8c']]])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A stock take needs a location, by `LocationID` or `Location`; a body with neither is not sent.
     */
    public function testAStockTakeWithoutALocationIsNotSent(): void
    {
        $body = ['EffectiveDate' => '2018-04-27T00:00:00', 'Account' => '403'];

        try {
            $this->connector()->send(new PostStockTake(StockTakePostData::from($body)));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['LocationID', 'Location'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostStockTake(StockTakePostData::from($body + ['Location' => 'Main Warehouse'])));

        $this->mock->assertSentCount(1);
    }

    /**
     * A stock transfer needs both locations, by ID or by name, and, in transit, the account holding
     * the stock and the date it left; a body without them is not sent.
     */
    public function testAStockTransferNeedsItsLocationsAndItsInTransitFields(): void
    {
        $body = ['Status' => 'DRAFT', 'CompletionDate' => '2017-12-19T00:00:00', 'Lines' => [['SKU' => 'Bread', 'TransferQuantity' => 1]]];
        $locations = ['FromLocation' => 'Main Warehouse', 'ToLocation' => 'Bin 1'];

        try {
            $this->connector()->send(new PostStockTransfer(StockTransferPostData::from($body)));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['From', 'FromLocation', 'To', 'ToLocation'], array_keys($exception->errors()));
        }

        try {
            $this->connector()->send(new PostStockTransfer(StockTransferPostData::from([...$body, ...$locations, 'Status' => 'IN TRANSIT'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['InTransitAccount', 'DepartureDate'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostStockTransfer(StockTransferPostData::from([...$body, ...$locations])));
        $this->connector()->send(new PostStockTransfer(StockTransferPostData::from([...$body, ...$locations, 'Status' => 'IN TRANSIT', 'InTransitAccount' => '715', 'DepartureDate' => '2018-03-12T00:00:00'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * An inventory write-off needs a location, by `LocationID` or `Location`, and a completed one
     * its `EffectiveDate`; a body without them is not sent, and each line needs its product.
     */
    public function testAnInventoryWriteOffNeedsItsLocationItsDateAndItsLineProducts(): void
    {
        $body = ['Status' => 'COMPLETED', 'Account' => '404'];

        try {
            $this->connector()->send(new PostInventoryWriteOff(InventoryWriteOffPostData::from($body + ['Lines' => [['Quantity' => 1]]])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['LocationID', 'Location', 'EffectiveDate', 'Lines.0.ProductID', 'Lines.0.ProductCode'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostInventoryWriteOff(InventoryWriteOffPostData::from($body + ['Location' => 'Main Warehouse', 'EffectiveDate' => '2018-01-12T00:00:00', 'Lines' => [['Quantity' => 1, 'ProductCode' => 'Bread']]])));
        $this->connector()->send(new PostInventoryWriteOff(InventoryWriteOffPostData::from(['Status' => 'DRAFT', 'Account' => '404', 'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A purchase needs a supplier, by name or by ID; a body with neither is not sent.
     */
    public function testAPurchaseWithoutASupplierIsNotSent(): void
    {
        try {
            $this->connector()->send(new PostPurchase(PurchasePostData::from(['Approach' => 'INVOICE', 'Location' => 'Main Warehouse'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['SupplierID', 'Supplier'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    public function testEitherSupplierFieldIsEnoughForAPurchase(): void
    {
        $this->connector()->send(new PostPurchase(PurchasePostData::from(['Supplier' => 'ABPA', 'Approach' => 'INVOICE', 'Location' => 'Main Warehouse'])));
        $this->connector()->send(new PutPurchase(PurchasePutData::from(['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'SupplierID' => 'f1d1696b-8988-4ca0-8b9d-60317e463d07', 'Approach' => 'STOCK', 'Location' => 'Main Warehouse'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * An advanced purchase, too, needs a supplier, by name or by ID; a body with neither is not
     * sent.
     */
    public function testAnAdvancedPurchaseWithoutASupplierIsNotSent(): void
    {
        try {
            $this->connector()->send(new PostAdvancedPurchase(AdvancedPurchasePostData::from(['Approach' => 'STOCK', 'Location' => 'Main Warehouse'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['SupplierID', 'Supplier'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * Either supplier field is enough for an advanced purchase, and its PUT, like the reference's
     * example, needs no `Approach`.
     */
    public function testEitherSupplierFieldIsEnoughForAnAdvancedPurchase(): void
    {
        $this->connector()->send(new PostAdvancedPurchase(AdvancedPurchasePostData::from(['Supplier' => 'ABPA', 'Approach' => 'STOCK', 'Location' => 'Main Warehouse'])));
        $this->connector()->send(new PutAdvancedPurchase(AdvancedPurchasePutData::from(['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'SupplierID' => '92c27d86-a8d3-4335-9da1-d3ebd82cb568', 'Location' => 'Main Warehouse'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * Every address table requires `Line1` and `Country`. The purchase responses send them as
     * `null`, so the address classes take `null`, but a sale's or a purchase's billing address, or
     * a purchase's shipping address, without them is not sent.
     */
    public function testAnAddressWithoutItsLine1AndCountryIsNotSent(): void
    {
        $address = ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA'];
        $purchase = static fn (array $billing, array $shipping): PurchasePostData => PurchasePostData::from(['Supplier' => 'ABPA', 'Approach' => 'INVOICE', 'Location' => 'Main Warehouse', 'BillingAddress' => $billing, 'ShippingAddress' => $shipping]);
        $sale = static fn (array $billing): SalePostData => SalePostData::from(['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1, 'BillingAddress' => $billing]);

        try {
            $this->connector()->send(new PostPurchase($purchase(['City' => 'Melbourne'], ['ShipToOther' => false])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['BillingAddress.Line1', 'BillingAddress.Country', 'ShippingAddress.Line1', 'ShippingAddress.Country'], array_keys($exception->errors()));
        }

        try {
            $this->connector()->send(new PostSale($sale(['City' => 'Melbourne'])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['BillingAddress.Line1', 'BillingAddress.Country'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostPurchase($purchase($address, $address)));
        $this->connector()->send(new PostSale($sale($address)));

        $this->mock->assertSentCount(2);
    }

    /**
     * Validation runs on what is sent: a field the request leaves out is not checked, like a
     * component's read-only `Name` beyond its 256 characters.
     */
    public function testAnOmittedFieldIsNotValidated(): void
    {
        $this->connector()->send(new PostProduct(ProductPostData::from(self::PRODUCT + [
            'BillOfMaterialsProducts' => [['ProductCode' => 'GB1-White', 'Quantity' => 2, 'Name' => str_repeat('x', 300)]],
        ])));

        $this->assertSame([['Quantity' => 2.0, 'ProductCode' => 'GB1-White']], $this->mock->lastPendingRequest()?->body()['BillOfMaterialsProducts']);
    }

    /**
     * A product with a bill of materials needs the quantity it makes and how its cost is
     * estimated; one without needs neither.
     */
    public function testABillOfMaterialsNeedsItsQuantityAndCostEstimation(): void
    {
        try {
            $this->connector()->send(new PostProduct(ProductPostData::from(self::PRODUCT + ['BillOfMaterial' => true])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['QuantityToProduce', 'AssemblyCostEstimationMethod'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostProduct(ProductPostData::from(self::PRODUCT + ['BillOfMaterial' => false])));
        $this->connector()->send(new PostProduct(ProductPostData::from(self::PRODUCT + ['BillOfMaterial' => true, 'QuantityToProduce' => 1, 'AssemblyCostEstimationMethod' => 'Average Cost'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * The reference requires one field of each of these pairs: a body with neither is not sent.
     *
     * @param array<string, mixed> $part
     * @param list<string> $fields
     */
    #[DataProvider('eitherFieldProvider')]
    public function testAProductPartWithNeitherFieldOfItsPairIsNotSent(array $part, array $fields): void
    {
        try {
            $this->connector()->send(new PostProduct(ProductPostData::from(self::PRODUCT + $part)));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame($fields, array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * @return array<string, array{array<string, mixed>, list<string>}>
     */
    public static function eitherFieldProvider(): array
    {
        return [
            'supplier' => [['Suppliers' => [['Cost' => 1]]], ['Suppliers.0.SupplierID', 'Suppliers.0.SupplierName']],
            'supplier option location' => [
                ['Suppliers' => [['SupplierName' => 'ABPA', 'ProductSupplierOptions' => [['Lead' => 3]]]]],
                ['Suppliers.0.ProductSupplierOptions.0.LocationID', 'Suppliers.0.ProductSupplierOptions.0.LocationName'],
            ],
            'reorder level location' => [['ReorderLevels' => [['PickZones' => 'A']]], ['ReorderLevels.0.LocationID', 'ReorderLevels.0.LocationName']],
            'component' => [['BillOfMaterialsProducts' => [['Quantity' => 1]]], ['BillOfMaterialsProducts.0.ComponentProductID', 'BillOfMaterialsProducts.0.ProductCode']],
            'service' => [['BillOfMaterialsServices' => [['Quantity' => 1]]], ['BillOfMaterialsServices.0.ComponentProductID', 'BillOfMaterialsServices.0.Name']],
            'custom price product' => [['CustomPrices' => [['Price' => 1.1, 'CustomerName' => 'ACME']]], ['CustomPrices.0.ProductID', 'CustomPrices.0.ProductSKU']],
            'custom price customer' => [['CustomPrices' => [['Price' => 1.1, 'ProductSKU' => 'Bread']]], ['CustomPrices.0.CustomerID', 'CustomPrices.0.CustomerName']],
        ];
    }

    /**
     * A price needs a product and a customer wherever it is nested, a customer's prices too.
     */
    public function testACustomersPriceNeedsItsCustomerAsWell(): void
    {
        $customer = Arr::except(Cin7Payloads::customer(), 'ID') + ['ProductPrices' => [['Price' => 1.1, 'ProductSKU' => 'Bread']]];

        try {
            $this->connector()->send(new PostCustomer(CustomerPostData::from($customer)));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['ProductPrices.0.CustomerID', 'ProductPrices.0.CustomerName'], array_keys($exception->errors()));
        }

        $customer['ProductPrices'][0]['CustomerName'] = 'ACME';
        $this->connector()->send(new PostCustomer(CustomerPostData::from($customer)));

        $this->mock->assertSentCount(1);
    }

    /**
     * A supply interval needs the fields of its delivery method: the days and start of an
     * `Interval`, each weekday of a `Fixed` one.
     */
    public function testASupplyIntervalNeedsTheFieldsOfItsDeliveryMethod(): void
    {
        $product = static fn (array $interval): array => self::PRODUCT + [
            'Suppliers' => [['SupplierName' => 'ABPA', 'ProductSupplierOptions' => [['LocationName' => 'Main Warehouse', 'SupplyIntervals' => [$interval]]]]],
        ];
        $at = 'Suppliers.0.ProductSupplierOptions.0.SupplyIntervals.0.';

        foreach ([
            'Interval' => ['IntervalDays', 'IntervalStartDate'],
            'Fixed' => ['IsMonday', 'IsTuesday', 'IsWednesday', 'IsThursday', 'IsFriday', 'IsSaturday', 'IsSunday'],
        ] as $method => $fields) {
            try {
                $this->connector()->send(new PostProduct(ProductPostData::from($product(['DeliveryMethod' => $method]))));
                $this->fail("The {$method} interval should have failed validation.");
            } catch (ValidationException $exception) {
                $this->assertSame(array_map(static fn (string $field): string => $at . $field, $fields), array_keys($exception->errors()));
            }
        }

        $this->connector()->send(new PostProduct(ProductPostData::from($product(['DeliveryMethod' => 'Interval', 'IntervalDays' => 7, 'IntervalStartDate' => '2017-12-25']))));

        $this->mock->assertSentCount(1);
    }

    /**
     * A `BANK` account needs its bank and account number on either verb; any other type needs
     * neither.
     */
    public function testABankAccountNeedsItsBankAndAccountNumber(): void
    {
        $account = ['Code' => '090', 'Name' => 'Business Bank Account', 'Type' => 'BANK', 'Status' => 'ACTIVE'];

        foreach ([
            'POST' => static fn (): WriteRequest => new PostAccount(AccountPostData::from($account)),
            'PUT' => static fn (): WriteRequest => new PutAccount(AccountPutData::from($account)),
        ] as $verb => $request) {
            try {
                $this->connector()->send($request());
                $this->fail("The {$verb} body should have failed validation.");
            } catch (ValidationException $exception) {
                $this->assertSame(['Bank', 'BankAccountNumber'], array_keys($exception->errors()), $verb);
            }
        }

        $this->connector()->send(new PostAccount(AccountPostData::from(['Type' => 'CURRLIAB'] + $account)));
        $this->connector()->send(new PutAccount(AccountPutData::from($account + ['Bank' => 'Bank of Example', 'BankAccountNumber' => '12345678'])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A markup line needs its start price and value unless it deletes the tier (`D`), and a tier
     * number is 1 to 10, and a value is not negative.
     *
     * @param array<string, mixed> $line
     * @param list<string> $fields
     */
    #[DataProvider('invalidMarkupLineProvider')]
    public function testAMarkupLineBreakingARuleIsNotSent(array $line, array $fields): void
    {
        try {
            $this->connector()->send(new PutProductMarkupPrices(MarkupPricesData::from(['ProductID' => '7c8795c2-1a6b-4318-ba72-291f61444906', 'MarkupPrices' => [$line]])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(array_map(static fn (string $field): string => 'MarkupPrices.0.' . $field, $fields), array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * @return array<string, array{array<string, mixed>, list<string>}>
     */
    public static function invalidMarkupLineProvider(): array
    {
        return [
            'a percentage without its start price or value' => [['TierNumber' => 1, 'MarkupType' => 'P'], ['UsePriceType', 'MarkupValue']],
            'a tier number above 10' => [['TierNumber' => 11, 'MarkupType' => 'D'], ['TierNumber']],
            'a negative value' => [['TierNumber' => 1, 'MarkupType' => 'A', 'UsePriceType' => 'A', 'MarkupValue' => -1], ['MarkupValue']],
        ];
    }

    public function testADeletedMarkupLineNeedsNeitherStartPriceNorValue(): void
    {
        $this->connector()->send(new PutProductMarkupPrices(MarkupPricesData::from(['ProductID' => '7c8795c2-1a6b-4318-ba72-291f61444906', 'MarkupPrices' => [['TierNumber' => 3, 'MarkupType' => 'D']]])));

        $this->mock->assertSentCount(1);
    }

    /**
     * An opportunity line needs its product, by `ProductID` or `ProductSku`; a body with a line that
     * has neither is not sent, and one of each is enough.
     */
    public function testAnOpportunityLineNeedsItsProduct(): void
    {
        $body = [
            'CustomerName' => 'ABC Furniture',
            'BillingAddressLine1' => 'Cosmonauts Alley',
            'Currency' => 'USD',
            'TaxRule' => 'GST on Income',
            'Terms' => '30 days',
            'PriceTier' => 'Tier 1',
            'OpportunityLocation' => 'Main Warehouse',
            'CustomerCurrency' => 'AED',
            'TermMethod' => 1,
            'SalesRepresentative' => 'DEFAULT business contact',
            'ShipToOther' => false,
        ];
        $line = ['Quantity' => 1, 'Price' => 2, 'Tax' => 0, 'Total' => 2];

        try {
            $this->connector()->send(new PostCrmOpportunity(OpportunityPostData::from($body + ['Lines' => [$line]])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(['Lines.0.ProductID', 'Lines.0.ProductSku'], array_keys($exception->errors()));
        }

        $this->connector()->send(new PostCrmOpportunity(OpportunityPostData::from($body + ['Lines' => [$line + ['ProductSku' => '101-Gloves-016']]])));
        $this->connector()->send(new PostCrmOpportunity(OpportunityPostData::from($body + ['Lines' => [$line + ['ProductID' => '87d7bc76-11d4-43e1-b022-488dae83868b']]])));

        $this->mock->assertSentCount(2);
    }

    /**
     * A webhook's credentials follow its authorisation type: `basicauth` needs a user name and a
     * password, `bearerauth` a token, and `noauth` none; a body without them is not sent.
     */
    public function testAWebhookNeedsTheCredentialsOfItsAuthorisationType(): void
    {
        $body = ['Type' => 'Sale/Created', 'IsActive' => true, 'ExternalURL' => 'https://example.test/hook'];

        foreach ([['basicauth', ['ExternalUserName', 'ExternalPassword']], ['bearerauth', ['ExternalBearerToken']]] as [$type, $fields]) {
            try {
                $this->connector()->send(new PostWebhooks(WebhookPostData::from($body + ['ExternalAuthorizationType' => $type])));
                $this->fail('The body should have failed validation.');
            } catch (ValidationException $exception) {
                $this->assertEqualsCanonicalizing($fields, array_keys($exception->errors()));
            }
        }

        $this->connector()->send(new PostWebhooks(WebhookPostData::from($body + ['ExternalAuthorizationType' => 'noauth'])));
        $this->connector()->send(new PostWebhooks(WebhookPostData::from($body + ['ExternalAuthorizationType' => 'basicauth', 'ExternalUserName' => 'u', 'ExternalPassword' => 'p'])));
        $this->connector()->send(new PostWebhooks(WebhookPostData::from($body + ['ExternalAuthorizationType' => 'bearerauth', 'ExternalBearerToken' => 't'])));

        $this->mock->assertSentCount(3);
    }

    /**
     * An array body is sent as given, so it is not validated.
     */
    public function testAnArrayBodyIsNotValidated(): void
    {
        $this->connector()->send(new PostSalePayment(['TaskID' => 'task-1', 'DatePaid' => 'tomorrow']));

        $this->assertSame(['TaskID' => 'task-1', 'DatePaid' => 'tomorrow'], $this->mock->lastPendingRequest()?->body());
    }
}
