<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Data;
use Hypervel\Data\Exceptions\CannotCreateData;
use Hypervel\Data\Support\Validation\ValidationPath;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Data\AbstractChargeData;
use Ipsocode\Cin7\Data\AbstractLineData;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\ErrorData;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Data\MoneyTaskList\MoneyTaskListData;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Product\ProductSupplierOptionIntervalData;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Sale\AbstractAddressData;
use Ipsocode\Cin7\Data\Sale\AbstractSaleData;
use Ipsocode\Cin7\Data\Sale\AbstractSalePaymentLineData;
use Ipsocode\Cin7\Data\Sale\CreditNote\AbstractSaleCreditNoteData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePartialData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePaymentData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Data\Sale\Invoice\AbstractSaleInvoiceData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePartialData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePutData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPutData;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Data\Sale\SaleManualJournalLineData;
use Ipsocode\Cin7\Data\Sale\SaleOrderData;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Ipsocode\Cin7\Requests\MoneyOperation\DeleteMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\GetMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\PostMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\PutMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyTaskList\GetMoneyTaskList;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Ipsocode\Cin7\Requests\Ref\Customer\Credits\GetCustomerCredits;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PostTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;
use Ipsocode\Cin7\Requests\Sale\CreditNote\DeleteSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\CreditNote\GetSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\CreditNote\PostSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\Invoice\DeleteSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\GetSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PostSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PutSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Order\GetSaleOrder;
use Ipsocode\Cin7\Requests\Sale\Order\PostSaleOrder;
use Ipsocode\Cin7\Requests\Sale\Payment\GetSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\PostSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\PutSalePayment;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionProperty;
use SplFileInfo;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per request with a response body, plus the conventions every class in `src/Data/`
 * is held to.
 *
 * @see docs/data.md
 */
class DataCatalogueTest extends TestCase
{
    /**
     * The properties the reference marks required, per class: not nullable, and with no default.
     *
     * @var array<class-string<Data>, list<string>>
     */
    private const array REQUIRED = [
        SaleInvoicePartialData::class => ['TaskID', 'CombineAdditionalCharges', 'Status', 'InvoiceDate', 'InvoiceDueDate'],
        SaleInvoicePostData::class => ['SaleID', 'TaskID', 'CombineAdditionalCharges', 'Status', 'InvoiceDate', 'InvoiceDueDate'],
        SaleInvoicePutData::class => ['SaleID', 'TaskID'],
        SaleCreditNotePartialData::class => ['TaskID', 'CombineAdditionalCharges', 'Status', 'CreditNoteDate'],
        SaleCreditNotePostData::class => ['SaleID', 'TaskID', 'CombineAdditionalCharges', 'CreditNoteInvoiceNumber', 'Status', 'CreditNoteDate'],
        SalePaymentLinePartialData::class => ['ID', 'TaskID', 'Type', 'Amount', 'DatePaid', 'Account', 'CurrencyRate'],
        SalePaymentPostData::class => ['TaskID', 'Type', 'Amount', 'DatePaid', 'Account', 'CurrencyRate'],
        SalePaymentPutData::class => ['ID'],
    ];

    /**
     * @param class-string<Cin7Request> $class
     * @param list<mixed> $args
     * @param array<string, mixed> $fixture
     * @param class-string<Data> $dataClass
     * @param string $path where the record or list sits in the fixture, empty for the whole body
     */
    #[DataProvider('dtoProvider')]
    public function testTheDtoIsTheDataClassAndRoundTripsTheFixture(
        string $class,
        array $args,
        array $fixture,
        string $dataClass,
        string $path,
    ): void {
        Saloon::fake([MockResponse::make($fixture)]);

        $response = $this->connector()->send(new $class(...$args));
        $dto = $response->dto();
        $expected = $path === '' ? $fixture : Arr::get($fixture, $path);

        if (array_is_list($expected)) {
            $this->assertIsArray($dto);
            $this->assertContainsOnlyInstancesOf($dataClass, $dto);
            $this->assertRoundTrips($expected, array_map(static fn (Data $item): array => $item->toArray(), $dto));
            $this->assertSame($response, $dto[0]->getResponse());

            return;
        }

        $this->assertInstanceOf($dataClass, $dto);
        $this->assertRoundTrips($expected, $dto->toArray());
        $this->assertSame($response, $dto->getResponse());
    }

    /**
     * @return array<string, array{string, list<mixed>, array<string, mixed>, string, string}>
     */
    public static function dtoProvider(): array
    {
        return [
            GetCustomer::class => [GetCustomer::class, [], Cin7Payloads::customerExample(), CustomerData::class, 'CustomerList'],
            PostCustomer::class => [PostCustomer::class, [[]], Cin7Payloads::customerSaved(), CustomerData::class, 'CustomerList.0'],
            PutCustomer::class => [PutCustomer::class, [[]], Cin7Payloads::customerSaved(), CustomerData::class, 'CustomerList.0'],
            GetProduct::class => [GetProduct::class, [], Cin7Payloads::productExample(), ProductData::class, 'Products'],
            PostProduct::class => [PostProduct::class, [[]], Cin7Payloads::productSaved(), ProductData::class, 'Products.0'],
            PutProduct::class => [PutProduct::class, [['ID' => 'guid-1']], Cin7Payloads::productSaved(), ProductData::class, 'Products.0'],
            GetTax::class => [GetTax::class, [], Cin7Payloads::taxList(), TaxData::class, 'TaxRuleList'],
            PostTax::class => [PostTax::class, [[]], Cin7Payloads::taxSaved(), TaxData::class, 'TaxRuleList.0'],
            PutTax::class => [PutTax::class, [[]], Cin7Payloads::taxSaved(), TaxData::class, 'TaxRuleList.0'],
            GetCustomerCredits::class => [
                GetCustomerCredits::class,
                [],
                Cin7Payloads::customerCreditsExample(),
                CustomerCreditData::class,
                'CustomerCredits',
            ],
            GetMoneyOperation::class => [GetMoneyOperation::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
            PostMoneyOperation::class => [PostMoneyOperation::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
            PutMoneyOperation::class => [PutMoneyOperation::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
            DeleteMoneyOperation::class => [DeleteMoneyOperation::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
            GetSale::class => [GetSale::class, ['guid-1'], Cin7Payloads::sale(), SaleData::class, ''],
            PostSale::class => [PostSale::class, [[]], Cin7Payloads::sale(), SaleData::class, ''],
            PutSale::class => [PutSale::class, [[]], Cin7Payloads::sale(), SaleData::class, ''],
            DeleteSale::class => [DeleteSale::class, ['guid-1'], Cin7Payloads::sale(), SaleData::class, ''],
            GetSaleOrder::class => [GetSaleOrder::class, ['sale-1'], Cin7Payloads::saleOrder(), SaleOrderData::class, ''],
            PostSaleOrder::class => [PostSaleOrder::class, [[]], Cin7Payloads::saleOrder(), SaleOrderData::class, ''],
            GetSaleInvoice::class => [GetSaleInvoice::class, ['sale-1'], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
            PostSaleInvoice::class => [PostSaleInvoice::class, [[]], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
            PutSaleInvoice::class => [PutSaleInvoice::class, [[]], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
            DeleteSaleInvoice::class => [DeleteSaleInvoice::class, ['task-1'], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
            GetSaleCreditNote::class => [GetSaleCreditNote::class, ['sale-1'], Cin7Payloads::saleCreditNotes(), SaleCreditNotesData::class, ''],
            PostSaleCreditNote::class => [PostSaleCreditNote::class, [[]], Cin7Payloads::saleCreditNotes(), SaleCreditNotesData::class, ''],
            DeleteSaleCreditNote::class => [DeleteSaleCreditNote::class, ['task-1'], Cin7Payloads::saleCreditNotes(), SaleCreditNotesData::class, ''],
            GetSalePayment::class => [GetSalePayment::class, ['sale-1'], Cin7Payloads::salePayments(), SalePaymentLinePartialData::class, ''],
            PostSalePayment::class => [PostSalePayment::class, [[]], Cin7Payloads::salePayment(), SalePaymentLinePartialData::class, ''],
            PutSalePayment::class => [PutSalePayment::class, [[]], Cin7Payloads::salePayment(), SalePaymentLinePartialData::class, ''],
            GetMoneyTaskList::class => [
                GetMoneyTaskList::class,
                [],
                Cin7Payloads::moneyTaskList(),
                MoneyTaskListData::class,
                'MoneyTasks',
            ],
            GetSaleList::class => [
                GetSaleList::class,
                [],
                Cin7Payloads::saleList(),
                SaleListData::class,
                'SaleList',
            ],
        ];
    }

    /**
     * The reference's Sale example carries no manual journal line, so one is added here.
     */
    public function testASaleWithAManualJournalLineRoundTrips(): void
    {
        $sale = Cin7Payloads::sale();
        $sale['ManualJournals']['Lines'] = [
            ['Reference' => 'Freight', 'Amount' => 12.5, 'Date' => '2017-11-22T00:00:00', 'Debit' => '610', 'Credit' => '200'],
        ];
        Saloon::fake([MockResponse::make($sale)]);

        $dto = $this->connector()->send(new GetSale('guid-1'))->dto();

        $this->assertInstanceOf(SaleManualJournalLineData::class, $dto->ManualJournals->Lines[0]);
        $this->assertRoundTrips($sale, $dto->toArray());
    }

    /**
     * The reference's Product example has no supplier, so one is added here, with an option and
     * a supply interval, to prove the three-deep nesting round-trips.
     */
    public function testAProductWithASupplierRoundTrips(): void
    {
        $product = Cin7Payloads::productExample();
        $product['Products'][0]['Suppliers'] = [[
            'SupplierID' => '42359698-352b-4354-89ed-e06feee1d567',
            'SupplierName' => 'ABPA',
            'ProductSupplierID' => '0d1ef9a2-1f26-4d2b-8d11-6f3d2cf0a001',
            'SupplierInventoryCode' => 'Test',
            'SupplierProductName' => 'Test Name',
            'Cost' => 1,
            'FixedCost' => 1,
            'Currency' => 'RUB',
            'DropShip' => false,
            'SupplierProductURL' => '',
            'LastSupplied' => '2017-12-25T00:00:00',
            'ProductSupplierOptions' => [[
                'ID' => '9a3b7c1e-0000-4000-8000-000000000001',
                'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                'LocationName' => 'Main Warehouse',
                'ReorderQuantity' => 5,
                'Lead' => 3,
                'Safety' => 1,
                'MinimumToReorder' => 2,
                'SupplyIntervals' => [[
                    'ID' => '9a3b7c1e-0000-4000-8000-000000000002',
                    'DeliveryMethod' => 'Interval',
                    'IntervalDays' => 7,
                    'IntervalStartDate' => '2017-12-25',
                    'IsMonday' => false,
                    'IsTuesday' => false,
                    'IsWednesday' => false,
                    'IsThursday' => false,
                    'IsFriday' => false,
                    'IsSaturday' => false,
                    'IsSunday' => false,
                ]],
            ]],
        ]];
        Saloon::fake([MockResponse::make($product)]);

        $dto = $this->connector()->send(new GetProduct)->dto();

        $this->assertInstanceOf(ProductSupplierOptionIntervalData::class, $dto[0]->Suppliers[0]->ProductSupplierOptions[0]->SupplyIntervals[0]);
        $this->assertRoundTrips($product['Products'], array_map(static fn (Data $item): array => $item->toArray(), $dto));
    }

    /**
     * `PriceTiers` is keyed by the account's tier names, so it stays a plain map.
     */
    public function testProductPriceTiersKeepTheirNames(): void
    {
        $product = Cin7Payloads::productExample();
        $product['Products'][0]['PriceTiers'] = ['Retail' => 8, 'Trade' => 6.5];
        Saloon::fake([MockResponse::make($product)]);

        $dto = $this->connector()->send(new GetProduct)->dto();

        $this->assertEquals(['Retail' => 8, 'Trade' => 6.5], $dto[0]->PriceTiers);
    }

    /**
     * The body models are not responses, so their fixtures, the reference's request examples,
     * round-trip through the class.
     *
     * @param class-string<Data> $class
     * @param array<string, mixed> $fixture
     */
    #[DataProvider('bodyProvider')]
    public function testTheBodyModelsRoundTripTheirFixtures(string $class, array $fixture): void
    {
        $this->assertRoundTrips($fixture, $class::from($fixture)->toArray());
    }

    /**
     * @return array<string, array{class-string<Data>, array<string, mixed>}>
     */
    public static function bodyProvider(): array
    {
        return [
            SaleInvoicePostData::class => [SaleInvoicePostData::class, Cin7Payloads::saleInvoicePost()],
            SaleInvoicePutData::class => [SaleInvoicePutData::class, Cin7Payloads::saleInvoicePut()],
            SaleCreditNotePostData::class => [SaleCreditNotePostData::class, Cin7Payloads::saleCreditNotePost()],
            SalePaymentPostData::class => [SalePaymentPostData::class, Cin7Payloads::salePaymentPost()],
            SalePaymentPutData::class => [SalePaymentPutData::class, Cin7Payloads::salePaymentPut()],
            ErrorData::class => [ErrorData::class, Cin7Payloads::error()],
        ];
    }

    /**
     * @param class-string<Data> $class
     * @param array<string, mixed> $payload
     */
    #[DataProvider('missingRequiredFieldProvider')]
    public function testAModelWithoutARequiredFieldCannotBeBuilt(string $class, array $payload): void
    {
        $this->expectException(CannotCreateData::class);

        $class::from($payload);
    }

    /**
     * @return array<string, array{class-string<Data>, array<string, mixed>}>
     */
    public static function missingRequiredFieldProvider(): array
    {
        return [
            'invoice POST without Status' => [SaleInvoicePostData::class, Arr::except(Cin7Payloads::saleInvoicePost(), 'Status')],
            'invoice PUT without TaskID' => [SaleInvoicePutData::class, Arr::except(Cin7Payloads::saleInvoicePut(), 'TaskID')],
            'invoice without InvoiceDate' => [SaleInvoicePartialData::class, Arr::except(Cin7Payloads::saleInvoicePartial(), 'InvoiceDate')],
            'credit note POST without CreditNoteInvoiceNumber' => [
                SaleCreditNotePostData::class,
                Arr::except(Cin7Payloads::saleCreditNotePost(), 'CreditNoteInvoiceNumber'),
            ],
            'credit note without CreditNoteDate' => [SaleCreditNotePartialData::class, Arr::except(Cin7Payloads::saleCreditNotePartial(), 'CreditNoteDate')],
            'payment POST without Type' => [SalePaymentPostData::class, Arr::except(Cin7Payloads::salePaymentPost(), 'Type')],
            'payment PUT without ID' => [SalePaymentPutData::class, Arr::except(Cin7Payloads::salePaymentPut(), 'ID')],
            'payment without TaskID' => [SalePaymentLinePartialData::class, Arr::except(Cin7Payloads::salePayment(), 'TaskID')],
        ];
    }

    /**
     * The credit note's payments carry more than a Sale Payment Line; `Type` and the numbers survive `dto()`.
     */
    public function testACreditNotePaymentKeepsItsTypeAndNumbers(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::saleCreditNotes())]);

        $payment = $this->connector()->send(new GetSaleCreditNote('sale-1', ['IncludePaymentInfo' => true]))->dto()->CreditNotes[0]->Payments[0];

        $this->assertInstanceOf(SaleCreditNotePaymentData::class, $payment);
        $this->assertSame('Refund', $payment->Type);
        $this->assertSame('CR-00001', $payment->CreditNoteNumber);
        $this->assertNull($payment->CreditID);
    }

    /**
     * Cin7's DateTime: `yyyy-MM-ddTHH:mm:ss`, an optional fraction of up to seven digits and an
     * optional `Z`; the `date` rule beside the pattern rejects an impossible date.
     */
    public function testTheDateTimeRuleMatchesCin7sFormat(): void
    {
        $this->assertSame(['regex:' . DateTime::PATTERN, 'date'], new DateTime()->getRules(ValidationPath::create()));

        foreach (['2017-11-30T00:00:00', '2012-11-14T13:28:33.363', '2017-11-22T06:58:21.8882229Z'] as $date) {
            $this->assertSame(1, preg_match(DateTime::PATTERN, $date), $date);
        }

        foreach (['2017-11-30', '2017-11-30T00:00', '2017-11-30T00:00:00+10:00', '2017-11-30 00:00:00', '2017-11-30T00:00:00.12345678'] as $date) {
            $this->assertSame(0, preg_match(DateTime::PATTERN, $date), $date);
        }
    }

    /**
     * Every model is a final class; an abstract class holds only the fields several models share
     * (the `Abstract…Data` classes): a model is a final child that adds its own fields.
     */
    public function testEveryDataClassIsFinalOrAnAbstractParentAndExtendsData(): void
    {
        $parents = [];

        foreach (self::dataClasses() as $class) {
            $reflection = new ReflectionClass($class);

            $this->assertTrue($reflection->isFinal() || $reflection->isAbstract(), $class);
            $this->assertTrue($reflection->isSubclassOf(Data::class), $class);

            if ($reflection->isAbstract()) {
                $parents[] = $class;
            }
        }

        $this->assertSame([
            AbstractChargeData::class,
            AbstractLineData::class,
            AbstractAddressData::class,
            AbstractSaleData::class,
            AbstractSalePaymentLineData::class,
            AbstractSaleCreditNoteData::class,
            AbstractSaleInvoiceData::class,
        ], $parents);
    }

    /**
     * A field the reference marks required is not nullable and has no default, so building the
     * model without it fails; every other field is nullable and defaults to `null`, which a write
     * leaves out of the body. The required fields come first, as PHP needs.
     */
    public function testOnlyTheRequiredFieldsHaveNoDefault(): void
    {
        foreach (self::dataClasses() as $class) {
            $reflection = new ReflectionClass($class);

            if ($reflection->isAbstract()) {
                continue;
            }

            $required = self::REQUIRED[$class] ?? [];
            $parameters = [];

            foreach ($reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
                $parameters[$parameter->getName()] = $parameter;
            }

            // Inherited and trait fields are properties with a default, not constructor parameters.
            foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $name = $class . '::$' . $property->getName();
                $parameter = $parameters[$property->getName()] ?? null;
                $hasDefault = $parameter?->isDefaultValueAvailable() ?? $property->hasDefaultValue();

                if (in_array($property->getName(), $required, true)) {
                    $this->assertFalse($property->getType()->allowsNull(), $name);
                    $this->assertFalse($hasDefault, $name);
                } else {
                    $this->assertTrue($property->getType()->allowsNull(), $name);
                    $this->assertTrue($hasDefault, $name);
                    $this->assertNull($parameter?->getDefaultValue() ?? $property->getDefaultValue(), $name);
                }
            }

            $this->assertSame($required, array_slice(array_keys($parameters), 0, count($required)), $class);
        }
    }

    public function testEveryResponseDataClassKeepsItsResponse(): void
    {
        foreach ([CustomerData::class, ProductData::class, TaxData::class, CustomerCreditData::class, MoneyTaskData::class, MoneyTaskListData::class, SaleData::class, SaleListData::class, SaleOrderData::class, SaleInvoicesData::class, SaleCreditNotesData::class, SalePaymentLinePartialData::class] as $class) {
            $this->assertInstanceOf(WithResponse::class, new ReflectionClass($class)->newInstanceWithoutConstructor());
        }
    }

    /**
     * Assert a model's `toArray()` holds the fixture: every fixture key is modelled under its
     * wire name with an equal value, and every other key is null, the default of a field the
     * fixture left out. Absent and `null` are the same to a model.
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     */
    private function assertRoundTrips(array $expected, array $actual, string $path = ''): void
    {
        foreach ($expected as $key => $value) {
            $at = $path === '' ? (string) $key : $path . '.' . $key;

            $this->assertArrayHasKey($key, $actual, $at);

            if (is_array($value) && is_array($actual[$key])) {
                $this->assertRoundTrips($value, $actual[$key], $at);
            } else {
                $this->assertEquals($value, $actual[$key], $at);
            }
        }

        foreach (array_diff_key($actual, $expected) as $key => $value) {
            $this->assertNull($value, $path === '' ? (string) $key : $path . '.' . $key);
        }
    }

    /**
     * Every class under `src/Data/` but its validation attributes in `Attributes/` and its traits
     * in `Concerns/`.
     *
     * @return list<class-string<Data>>
     */
    private static function dataClasses(): array
    {
        $root = __DIR__ . '/../../../src/Data';
        $classes = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            $relative = str_replace($root . '/', '', $file->getPathname());

            if ($file->getExtension() !== 'php' || str_starts_with($relative, 'Attributes/') || str_starts_with($relative, 'Concerns/')) {
                continue;
            }

            $classes[] = 'Ipsocode\Cin7\Data\\' . str_replace(['/', '.php'], ['\\', ''], $relative);
        }

        sort($classes);

        return $classes;
    }
}
