<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Data\Sale\SaleManualJournalLineData;
use Ipsocode\Cin7\Data\Sale\SaleOrderData;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Requests\Cin7Request;
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
use Ipsocode\Cin7\Requests\Sale\Payment\DeleteSalePayment;
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
        foreach ([TaxData::class, CustomerCreditData::class, SaleData::class, SaleListData::class, SaleOrderData::class, SaleInvoicesData::class, SaleCreditNotesData::class, SalePaymentLinePartialData::class] as $class) {
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
