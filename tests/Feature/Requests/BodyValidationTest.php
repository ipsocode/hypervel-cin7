<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Closure;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Hypervel\Validation\ValidationException;
use Ipsocode\Cin7\Data\Customer\CustomerPostData;
use Ipsocode\Cin7\Data\Product\ProductPostData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPutData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPutData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePutData;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\SalePostData;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Sale\CreditNote\PostSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\PostSaleFulfilmentPack;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PostSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PutSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PostSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PutSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Invoice\PostSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PutSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Order\PostSaleOrder;
use Ipsocode\Cin7\Requests\Sale\Payment\PostSalePayment;
use Ipsocode\Cin7\Requests\Sale\PostSale;
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
        ];
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
     * An array body is sent as given, so it is not validated.
     */
    public function testAnArrayBodyIsNotValidated(): void
    {
        $this->connector()->send(new PostSalePayment(['TaskID' => 'task-1', 'DatePaid' => 'tomorrow']));

        $this->assertSame(['TaskID' => 'task-1', 'DatePaid' => 'tomorrow'], $this->mock->lastPendingRequest()?->body());
    }
}
