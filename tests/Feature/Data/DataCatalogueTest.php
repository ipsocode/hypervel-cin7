<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Support\Validation\ValidationPath;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\ErrorData;
use Ipsocode\Cin7\Data\Product\ProductSupplierOptionIntervalData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePaymentData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalLineData;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Sale\CreditNote\GetSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
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
 * @see src/docs/data.md
 */
class DataCatalogueTest extends TestCase
{
    use RoundTripsFixtures;

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
     * Every model is reached from a catalog row: it is a `dto()` class or a body class, or a
     * property of one of those, at any depth. The Error Model is read from failed responses.
     */
    public function testEveryDataClassIsReachedFromACatalogueRow(): void
    {
        $queue = [
            ErrorData::class,
            ...array_column(Catalogue::rows('dtos'), 3),
            ...array_column(Catalogue::rows('bodies'), 0),
            ...array_column(Catalogue::rows('missing'), 0),
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
        $body = Cin7Fake::error('Request is invalid')->body()->all();

        $this->assertRoundTrips($body, ErrorData::from($body)->toArray());
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
     * (the `Abstract…Data` classes), so another class extends it: a model is a final child that
     * adds its own fields.
     */
    public function testEveryDataClassIsFinalOrAnAbstractParentAndExtendsData(): void
    {
        $abstract = [];
        $extended = [];

        foreach (self::dataClasses() as $class) {
            $reflection = new ReflectionClass($class);

            $this->assertTrue($reflection->isFinal() || $reflection->isAbstract(), $class);
            $this->assertTrue($reflection->isSubclassOf(Data::class), $class);

            if ($reflection->isAbstract()) {
                $abstract[] = $class;
            }

            $extended[$reflection->getParentClass()->getName()] = true;
        }

        $this->assertNotEmpty($abstract);

        foreach ($abstract as $class) {
            $this->assertArrayHasKey($class, $extended, "{$class} is abstract and nothing extends it.");
        }
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

    /**
     * The class each request's `dto()` returns, a `dtos` catalogue row's data class, keeps the
     * response it was built from.
     */
    public function testEveryResponseDataClassKeepsItsResponse(): void
    {
        $classes = array_unique(array_column(Catalogue::rows('dtos'), 3));

        $this->assertNotEmpty($classes);

        foreach ($classes as $class) {
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
