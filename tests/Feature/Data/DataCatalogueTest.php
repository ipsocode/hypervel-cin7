<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Data\MoneyTaskList\MoneyTaskListData;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Product\ProductSupplierOptionIntervalData;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
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
use ReflectionNamedType;
use ReflectionUnionType;
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
            $this->assertEquals($expected, array_map(static fn (Data $item): array => $item->toArray(), $dto));
            $this->assertSame($response, $dto[0]->getResponse());

            return;
        }

        $this->assertInstanceOf($dataClass, $dto);
        $this->assertEquals($expected, $dto->toArray());
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
            GetSalePayment::class => [GetSalePayment::class, ['sale-1'], [Cin7Payloads::salePayment()], SalePaymentLinePartialData::class, ''],
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
        $this->assertEquals($sale, $dto->toArray());
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
        $this->assertEquals($product['Products'], array_map(static fn (Data $item): array => $item->toArray(), $dto));
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
     * The POST models are bodies, not responses, so their fixtures round-trip through the class.
     */
    public function testThePostModelsRoundTripTheirFixtures(): void
    {
        $invoice = Cin7Payloads::saleInvoicePost();
        $creditNote = Cin7Payloads::saleCreditNotePost();

        $this->assertEquals($invoice, SaleInvoicePostData::from($invoice)->toArray());
        $this->assertEquals($creditNote, SaleCreditNotePostData::from($creditNote)->toArray());
    }

    public function testEveryDataClassIsFinalAndExtendsData(): void
    {
        foreach (self::dataClasses() as $class) {
            $reflection = new ReflectionClass($class);

            $this->assertTrue($reflection->isFinal(), $class);
            $this->assertTrue($reflection->isSubclassOf(Data::class), $class);
        }
    }

    public function testEveryConstructorPropertyAdmitsOptional(): void
    {
        foreach (self::dataClasses() as $class) {
            foreach (new ReflectionClass($class)->getConstructor()->getParameters() as $parameter) {
                $type = $parameter->getType();
                $names = $type instanceof ReflectionUnionType
                    ? array_map(static fn (ReflectionNamedType $named): string => $named->getName(), $type->getTypes())
                    : [$type->getName()];

                $this->assertContains(Optional::class, $names, $class . '::$' . $parameter->getName());
            }
        }
    }

    public function testEveryResponseDataClassKeepsItsResponse(): void
    {
        foreach ([CustomerData::class, ProductData::class, TaxData::class, CustomerCreditData::class, SaleData::class, SaleListData::class, SaleOrderData::class, SaleInvoicesData::class, SaleCreditNotesData::class, SalePaymentLinePartialData::class] as $class) {
            $this->assertInstanceOf(WithResponse::class, new ReflectionClass($class)->newInstanceWithoutConstructor());
        }
    }

    /**
     * Every class under `src/Data/`.
     *
     * @return list<class-string<Data>>
     */
    private static function dataClasses(): array
    {
        $root = __DIR__ . '/../../../src/Data';
        $classes = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative = str_replace($root . '/', '', $file->getPathname());
            $classes[] = 'Ipsocode\Cin7\Data\\' . str_replace(['/', '.php'], ['\\', ''], $relative);
        }

        sort($classes);

        return $classes;
    }
}
