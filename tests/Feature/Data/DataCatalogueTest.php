<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Exceptions\CannotCreateData;
use Hypervel\Data\Support\Validation\ValidationPath;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractAddressData;
use Ipsocode\Cin7\Data\AbstractChargeData;
use Ipsocode\Cin7\Data\AbstractLineData;
use Ipsocode\Cin7\Data\AbstractManualJournalLineData;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\AbstractPurchaseData;
use Ipsocode\Cin7\Data\AbstractPurchaseInvoiceData;
use Ipsocode\Cin7\Data\AbstractPurchaseListData;
use Ipsocode\Cin7\Data\AbstractPurchaseManualJournalData;
use Ipsocode\Cin7\Data\AbstractPurchasePaymentData;
use Ipsocode\Cin7\Data\AbstractPurchaseStockLineData;
use Ipsocode\Cin7\Data\AbstractSaleListData;
use Ipsocode\Cin7\Data\AbstractSalePaymentLineData;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNotesData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoicesData;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchaseManualJournalsData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AbstractAdvancedPurchasePutAwayData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwaysData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AbstractAdvancedPurchaseStockData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStocksData;
use Ipsocode\Cin7\Data\Customer\AbstractCustomerData;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\Me\Addresses\AbstractMeAddressData;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressData;
use Ipsocode\Cin7\Data\Me\Contacts\AbstractMeContactData;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactData;
use Ipsocode\Cin7\Data\Me\MeData;
use Ipsocode\Cin7\Data\MoneyTask\AbstractMoneyTaskData;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Data\MoneyTaskList\MoneyTaskListData;
use Ipsocode\Cin7\Data\Other\ErrorData;
use Ipsocode\Cin7\Data\Product\AbstractProductData;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Product\ProductSupplierOptionIntervalData;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentsData;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalData;
use Ipsocode\Cin7\Data\Purchase\Order\AbstractPurchaseOrderData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderData;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentData;
use Ipsocode\Cin7\Data\Purchase\PurchaseData;
use Ipsocode\Cin7\Data\Purchase\Stock\AbstractPurchaseStockData;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockData;
use Ipsocode\Cin7\Data\PurchaseCreditNoteList\PurchaseCreditNoteListData;
use Ipsocode\Cin7\Data\PurchaseList\PurchaseListData;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Data\Ref\Supplier\Deposits\SupplierDepositData;
use Ipsocode\Cin7\Data\Ref\Tax\AbstractTaxData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Sale\AbstractSaleData;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentsData;
use Ipsocode\Cin7\Data\Sale\CreditNote\AbstractSaleCreditNoteData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePaymentData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\AbstractSaleFulfilmentPickPackTaskData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\AbstractSaleFulfilmentShipTaskData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Data\Sale\Invoice\AbstractSaleInvoiceData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\AbstractSaleManualJournalData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalLineData;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Data\Sale\Quote\AbstractSaleQuoteData;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuoteData;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Data\SaleCreditNoteList\SaleCreditNoteListData;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Data\Supplier\AbstractSupplierData;
use Ipsocode\Cin7\Data\Supplier\SupplierData;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Sale\CreditNote\GetSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionEnum;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use SplFileInfo;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per request with a response body, from the per-path files under
 * `tests/Fixtures/Catalogue/`, plus the conventions every class in `src/Data/` is held to.
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
        return Catalogue::rows('dtos');
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
        return Catalogue::rows('bodies');
    }

    /**
     * Every model is reached from a catalogue row: it is a `dto()` class or a body class, or a
     * property of one of those, at any depth. The Error Model is read from failed responses.
     */
    public function testEveryDataClassIsReachedFromACatalogueRow(): void
    {
        $queue = [
            ErrorData::class,
            ...array_column(self::dtoProvider(), 3),
            ...array_column(self::bodyProvider(), 0),
            ...array_column(self::missingRequiredFieldProvider(), 0),
            ...array_keys(Catalogue::rows('required')),
        ];
        $reached = [];

        while ($queue !== []) {
            $class = array_shift($queue);

            if (isset($reached[$class])) {
                continue;
            }

            $reached[$class] = true;

            foreach (new ReflectionClass($class)->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $type = $property->getType();
                $types = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];

                foreach ($types as $named) {
                    if ($named instanceof ReflectionNamedType && ! $named->isBuiltin() && is_a($named->getName(), Data::class, true)) {
                        $queue[] = $named->getName();
                    }
                }

                foreach ($property->getAttributes(DataCollectionOf::class) as $attribute) {
                    $queue[] = $attribute->newInstance()->class;
                }
            }
        }

        foreach (self::dataClasses() as $class) {
            if (! new ReflectionClass($class)->isAbstract()) {
                $this->assertArrayHasKey($class, $reached, "No catalogue row reaches {$class}.");
            }
        }
    }

    /**
     * Every enum in `src/Enums/` is string-backed, so its cases are the wire values, and types a
     * field of at least one model or a parameter of at least one request.
     */
    public function testEveryEnumIsStringBackedAndTypesAFieldOrParameter(): void
    {
        $used = [];

        $requests = realpath(__DIR__ . '/../../../src/Requests');

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator((string) $requests)) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $class = 'Ipsocode\Cin7\Requests\\' . str_replace(['/', '.php'], ['\\', ''], substr($file->getPathname(), strlen((string) $requests) + 1));

            foreach (new ReflectionClass($class)->getConstructor()?->getParameters() ?? [] as $parameter) {
                $type = $parameter->getType();

                foreach ($type instanceof ReflectionUnionType ? $type->getTypes() : [$type] as $named) {
                    if ($named instanceof ReflectionNamedType && enum_exists($named->getName())) {
                        $used[$named->getName()] = true;
                    }
                }
            }
        }

        foreach (self::dataClasses() as $class) {
            foreach (new ReflectionClass($class)->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $type = $property->getType();

                foreach ($type instanceof ReflectionUnionType ? $type->getTypes() : [$type] as $named) {
                    if ($named instanceof ReflectionNamedType && enum_exists($named->getName())) {
                        $used[$named->getName()] = true;
                    }
                }
            }
        }

        foreach (glob(__DIR__ . '/../../../src/Enums/*.php') ?: [] as $file) {
            $enum = 'Ipsocode\Cin7\Enums\\' . basename($file, '.php');

            $this->assertSame('string', (string) new ReflectionEnum($enum)->getBackingType(), $enum);
            $this->assertArrayHasKey($enum, $used, "No model field or request parameter is typed {$enum}.");
        }
    }

    /**
     * The Error Model is read from a failed response, not from a path's `dto()`.
     */
    public function testTheErrorModelRoundTrips(): void
    {
        $this->assertRoundTrips(Cin7Payloads::error(), ErrorData::from(Cin7Payloads::error())->toArray());
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
        return Catalogue::rows('missing');
    }

    /**
     * The credit note's payments carry more than a Sale Payment Line; `Type` and the numbers survive `dto()`.
     */
    public function testACreditNotePaymentKeepsItsTypeAndNumbers(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::saleCreditNotes())]);

        $payment = $this->connector()->send(new GetSaleCreditNote('sale-1', includePaymentInfo: true))->dto()->CreditNotes[0]->Payments[0];

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
            AbstractAddressData::class,
            AbstractChargeData::class,
            AbstractLineData::class,
            AbstractManualJournalLineData::class,
            AbstractPurchaseCreditNoteData::class,
            AbstractPurchaseData::class,
            AbstractPurchaseInvoiceData::class,
            AbstractPurchaseListData::class,
            AbstractPurchaseManualJournalData::class,
            AbstractPurchasePaymentData::class,
            AbstractPurchaseStockLineData::class,
            AbstractSaleListData::class,
            AbstractSalePaymentLineData::class,
            AbstractAdvancedPurchasePutAwayData::class,
            AbstractAdvancedPurchaseStockData::class,
            AbstractCustomerData::class,
            AbstractMeAddressData::class,
            AbstractMeContactData::class,
            AbstractMoneyTaskData::class,
            AbstractProductData::class,
            AbstractPurchaseOrderData::class,
            AbstractPurchaseStockData::class,
            AbstractTaxData::class,
            AbstractSaleData::class,
            AbstractSaleCreditNoteData::class,
            AbstractSaleFulfilmentPickPackTaskData::class,
            AbstractSaleFulfilmentShipTaskData::class,
            AbstractSaleInvoiceData::class,
            AbstractSaleManualJournalData::class,
            AbstractSaleQuoteData::class,
            AbstractSupplierData::class,
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

            $required = Catalogue::rows('required')[$class] ?? [];
            $parameters = [];

            foreach ($reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
                $parameters[$parameter->getName()] = $parameter;
            }

            // A parent's optional and trait fields are properties with a default; its required
            // fields reach the child through the constructor.
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
        foreach ([CustomerData::class, ProductData::class, TaxData::class, CustomerCreditData::class, MoneyTaskData::class, MoneyTaskListData::class, SaleData::class, SaleListData::class, SaleOrderData::class, SaleQuoteData::class, SaleManualJournalData::class, SaleAttachmentsData::class, SaleCreditNoteListData::class, SaleFulfilmentsData::class, SaleFulfilmentPickData::class, SaleFulfilmentPackData::class, SaleFulfilmentShipData::class, SaleInvoicesData::class, SaleCreditNotesData::class, SalePaymentLinePartialData::class, SupplierData::class, SupplierDepositData::class, MeData::class, MeAddressData::class, MeContactData::class, PurchasePaymentData::class, PurchaseOrderData::class, PurchaseStockData::class, PurchaseListData::class, PurchaseCreditNoteListData::class, PurchaseManualJournalData::class, PurchaseAttachmentsData::class, AdvancedPurchaseStocksData::class, PurchaseInvoiceData::class, PurchaseCreditNoteData::class, AdvancedPurchaseManualJournalsData::class, AdvancedPurchaseInvoicesData::class, AdvancedPurchasePutAwaysData::class, AdvancedPurchasePaymentData::class, AdvancedPurchaseCreditNotesData::class, PurchaseData::class, AdvancedPurchaseData::class] as $class) {
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
            $relative = str_replace($root . '/', '', $file->getPathname());

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $classes[] = 'Ipsocode\Cin7\Data\\' . str_replace(['/', '.php'], ['\\', ''], $relative);
        }

        sort($classes);

        return $classes;
    }
}
