<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Closure;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use InvalidArgumentException;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePutData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPutData;
use Ipsocode\Cin7\Data\Sale\SaleOrderData;
use Ipsocode\Cin7\Data\Sale\SalePostPutData;
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
use SplFileInfo;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per concrete request class, plus the folder/path and verb/name conventions
 * every class in `src/Requests/` is held to.
 *
 * @see docs/requests.md
 */
class RequestCatalogueTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 16, MockResponse::make(Cin7Payloads::customerList())));
    }

    /**
     * @param class-string<Cin7Request> $class
     * @param list<mixed> $args
     * @param array<string, mixed> $query
     * @param null|array<string, mixed> $body
     */
    #[DataProvider('requestProvider')]
    public function testTheRequestBuildsAsDocumented(
        string $class,
        array $args,
        Method $method,
        string $path,
        array $query,
        ?array $body,
    ): void {
        // A data object needs the booted container, which a provider runs before; its row
        // passes a closure instead.
        $args = array_map(static fn (mixed $arg): mixed => $arg instanceof Closure ? $arg() : $arg, $args);

        $this->connector()->send(new $class(...$args));

        $pending = $this->mock->lastPendingRequest();

        $this->assertNotNull($pending);
        $this->assertSame($method, $pending->method());
        $this->assertSame($path, $pending->uri()->getPath());
        $this->assertSame($query, $pending->queryParameters());
        $this->assertSame($body, $pending->body());
    }

    /**
     * @return array<string, array{string, list<mixed>, Method, string, array<string, mixed>, null|array<string, mixed>}>
     */
    public static function requestProvider(): array
    {
        return [
            GetCustomer::class => [
                GetCustomer::class,
                [],
                Method::GET,
                '/ExternalApi/v2/customer',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            PostCustomer::class => [
                PostCustomer::class,
                [['Name' => 'ACME']],
                Method::POST,
                '/ExternalApi/v2/customer',
                [],
                ['Name' => 'ACME'],
            ],
            PutCustomer::class => [
                PutCustomer::class,
                [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME']],
                Method::PUT,
                '/ExternalApi/v2/customer',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'],
            ],
            DeleteMoneyOperation::class => [
                DeleteMoneyOperation::class,
                ['b039f19e-66f8-4309-a4b1-abf928303c88', ['Void' => true]],
                Method::DELETE,
                '/ExternalApi/v2/moneyOperation',
                ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
                null,
            ],
            GetMoneyOperation::class => [
                GetMoneyOperation::class,
                ['b039f19e-66f8-4309-a4b1-abf928303c88'],
                Method::GET,
                '/ExternalApi/v2/moneyOperation',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
                null,
            ],
            PostMoneyOperation::class => [
                PostMoneyOperation::class,
                [['TaskType' => 'Receive Money']],
                Method::POST,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskType' => 'Receive Money'],
            ],
            PutMoneyOperation::class => [
                PutMoneyOperation::class,
                [['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED']],
                Method::PUT,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
            ],
            GetMoneyTaskList::class => [
                GetMoneyTaskList::class,
                [['Status' => 'COMPLETED', 'TaskType' => 'Spend Money']],
                Method::GET,
                '/ExternalApi/v2/moneyTaskList',
                ['Status' => 'COMPLETED', 'TaskType' => 'Spend Money', 'page' => 1, 'limit' => 100],
                null,
            ],
            GetProduct::class => [
                GetProduct::class,
                [],
                Method::GET,
                '/ExternalApi/v2/product',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            PostProduct::class => [
                PostProduct::class,
                [['Name' => 'Widget']],
                Method::POST,
                '/ExternalApi/v2/product',
                [],
                ['Name' => 'Widget'],
            ],
            PutProduct::class => [
                PutProduct::class,
                [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget']],
                Method::PUT,
                '/ExternalApi/v2/product',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget'],
            ],
            GetCustomerCredits::class => [
                GetCustomerCredits::class,
                [],
                Method::GET,
                '/ExternalApi/v2/ref/customer/credits',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            GetTax::class => [
                GetTax::class,
                [],
                Method::GET,
                '/ExternalApi/v2/ref/tax',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            PostTax::class => [
                PostTax::class,
                [['Name' => 'VAT']],
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            PutTax::class => [
                PutTax::class,
                [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT']],
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
            ],
            GetSaleList::class => [
                GetSaleList::class,
                [['Status' => 'ORDERED', 'ReadyForShipping' => true]],
                Method::GET,
                '/ExternalApi/v2/saleList',
                ['Status' => 'ORDERED', 'ReadyForShipping' => 'true', 'page' => 1, 'limit' => 100],
                null,
            ],
            DeleteSaleCreditNote::class => [
                DeleteSaleCreditNote::class,
                ['b039f19e-66f8-4309-a4b1-abf928303c88', ['Void' => false]],
                Method::DELETE,
                '/ExternalApi/v2/sale/creditnote',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'false'],
                null,
            ],
            GetSaleCreditNote::class => [
                GetSaleCreditNote::class,
                ['916ab4c0-6ccb-4c93-873d-0603859050e4', ['IncludePaymentInfo' => true]],
                Method::GET,
                '/ExternalApi/v2/sale/creditnote',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludePaymentInfo' => 'true'],
                null,
            ],
            PostSaleCreditNote::class => [
                PostSaleCreditNote::class,
                [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']],
                Method::POST,
                '/ExternalApi/v2/sale/creditnote',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            ],
            DeleteSale::class => [
                DeleteSale::class,
                ['0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ['Void' => true]],
                Method::DELETE,
                '/ExternalApi/v2/sale',
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Void' => 'true'],
                null,
            ],
            GetSale::class => [
                GetSale::class,
                ['0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ['IncludeTransactions' => true]],
                Method::GET,
                '/ExternalApi/v2/sale',
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'IncludeTransactions' => 'true'],
                null,
            ],
            DeleteSaleInvoice::class => [
                DeleteSaleInvoice::class,
                ['b039f19e-66f8-4309-a4b1-abf928303c88', ['Void' => true]],
                Method::DELETE,
                '/ExternalApi/v2/sale/invoice',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
                null,
            ],
            GetSaleInvoice::class => [
                GetSaleInvoice::class,
                ['916ab4c0-6ccb-4c93-873d-0603859050e4', ['CombineAdditionalCharges' => true]],
                Method::GET,
                '/ExternalApi/v2/sale/invoice',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => 'true'],
                null,
            ],
            PostSaleInvoice::class => [
                PostSaleInvoice::class,
                [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000']],
                Method::POST,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000'],
            ],
            PutSaleInvoice::class => [
                PutSaleInvoice::class,
                [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88']],
                Method::PUT,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            ],
            GetSaleOrder::class => [
                GetSaleOrder::class,
                ['916ab4c0-6ccb-4c93-873d-0603859050e4', ['IncludeProductInfo' => true]],
                Method::GET,
                '/ExternalApi/v2/sale/order',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludeProductInfo' => 'true'],
                null,
            ],
            PostSaleOrder::class => [
                PostSaleOrder::class,
                [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK']],
                Method::POST,
                '/ExternalApi/v2/sale/order',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK'],
            ],
            DeleteSalePayment::class => [
                DeleteSalePayment::class,
                ['ee093a0c-d177-9728-1df5-628a61a939e4'],
                Method::DELETE,
                '/ExternalApi/v2/sale/payment',
                ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4'],
                null,
            ],
            GetSalePayment::class => [
                GetSalePayment::class,
                ['916ab4c0-6ccb-4c93-873d-0603859050e4'],
                Method::GET,
                '/ExternalApi/v2/sale/payment',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
                null,
            ],
            PostSalePayment::class => [
                PostSalePayment::class,
                [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5]],
                Method::POST,
                '/ExternalApi/v2/sale/payment',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5],
            ],
            PutSalePayment::class => [
                PutSalePayment::class,
                [['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5]],
                Method::PUT,
                '/ExternalApi/v2/sale/payment',
                [],
                ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
            ],
            PostSale::class => [
                PostSale::class,
                [['Customer' => 'ACME']],
                Method::POST,
                '/ExternalApi/v2/sale',
                [],
                ['Customer' => 'ACME'],
            ],
            PutSale::class => [
                PutSale::class,
                [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush']],
                Method::PUT,
                '/ExternalApi/v2/sale',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'],
            ],
            PostTax::class . ' with data' => [
                PostTax::class,
                [fn (): TaxData => TaxData::from(['Name' => 'VAT'])],
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            PutTax::class . ' with data' => [
                PutTax::class,
                [fn (): TaxData => TaxData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'])],
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
            ],
            PostSaleOrder::class . ' with data' => [
                PostSaleOrder::class,
                [fn (): SaleOrderData => SaleOrderData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Memo' => 'Rush'])],
                Method::POST,
                '/ExternalApi/v2/sale/order',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Memo' => 'Rush'],
            ],
            PostSaleInvoice::class . ' with data' => [
                PostSaleInvoice::class,
                [fn (): SaleInvoicePostData => SaleInvoicePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'Memo' => 'Rush', 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00'])],
                Method::POST,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00', 'Memo' => 'Rush'],
            ],
            PutSaleInvoice::class . ' with data' => [
                PutSaleInvoice::class,
                [fn (): SaleInvoicePutData => SaleInvoicePutData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Lines' => []])],
                Method::PUT,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Lines' => []],
            ],
            PostSaleCreditNote::class . ' with data' => [
                PostSaleCreditNote::class,
                [fn (): SaleCreditNotePostData => SaleCreditNotePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Memo' => 'Damaged', 'Status' => 'AUTHORISED', 'CreditNoteDate' => '2017-11-22T00:00:00'])],
                Method::POST,
                '/ExternalApi/v2/sale/creditnote',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Status' => 'AUTHORISED', 'CreditNoteDate' => '2017-11-22T00:00:00', 'Memo' => 'Damaged'],
            ],
            PostSalePayment::class . ' with data' => [
                PostSalePayment::class,
                [fn (): SalePaymentPostData => SalePaymentPostData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0])],
                Method::POST,
                '/ExternalApi/v2/sale/payment',
                [],
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0],
            ],
            PutSalePayment::class . ' with data' => [
                PutSalePayment::class,
                [fn (): SalePaymentPutData => SalePaymentPutData::from(['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5])],
                Method::PUT,
                '/ExternalApi/v2/sale/payment',
                [],
                ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
            ],
            PostCustomer::class . ' with data' => [
                PostCustomer::class,
                [fn (): CustomerData => CustomerData::from(['Name' => 'ACME', 'AdditionalAttribute10' => 'x', 'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']]])],
                Method::POST,
                '/ExternalApi/v2/customer',
                [],
                ['Name' => 'ACME', 'AdditionalAttribute10' => 'x', 'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']]],
            ],
            PutCustomer::class . ' with data' => [
                PutCustomer::class,
                [fn (): CustomerData => CustomerData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxNumber' => null])],
                Method::PUT,
                '/ExternalApi/v2/customer',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'],
            ],
            PostProduct::class . ' with data' => [
                PostProduct::class,
                [fn (): ProductData => ProductData::from(['SKU' => 'Bread', 'PriceTiers' => ['Tier 1' => 8.0], 'ReorderLevels' => [['LocationName' => 'Main Warehouse', 'PickZones' => 'test']]])],
                Method::POST,
                '/ExternalApi/v2/product',
                [],
                ['SKU' => 'Bread', 'PriceTiers' => ['Tier 1' => 8.0], 'ReorderLevels' => [['LocationName' => 'Main Warehouse', 'PickZones' => 'test']]],
            ],
            PutProduct::class . ' with data' => [
                PutProduct::class,
                [fn (): ProductData => ProductData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Sellable' => false])],
                Method::PUT,
                '/ExternalApi/v2/product',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Sellable' => false],
            ],
            PostSale::class . ' with data' => [
                PostSale::class,
                [fn (): SalePostPutData => SalePostPutData::from(['Customer' => 'ACME', 'SkipQuote' => false])],
                Method::POST,
                '/ExternalApi/v2/sale',
                [],
                ['Customer' => 'ACME', 'SkipQuote' => false],
            ],
            PutSale::class . ' with data' => [
                PutSale::class,
                [fn (): SalePostPutData => SalePostPutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'ShippingAddress' => ['Line1' => '1 High St', 'Country' => 'UK']])],
                Method::PUT,
                '/ExternalApi/v2/sale',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'ShippingAddress' => ['Line1' => '1 High St', 'Country' => 'UK']],
            ],
            PostMoneyOperation::class . ' with data' => [
                PostMoneyOperation::class,
                [fn (): MoneyTaskData => MoneyTaskData::from(['TaskType' => 'Receive Money', 'Lines' => [['Name' => 'Bread', 'Quantity' => 3]]])],
                Method::POST,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskType' => 'Receive Money', 'Lines' => [['Name' => 'Bread', 'Quantity' => 3.0]]],
            ],
            PutMoneyOperation::class . ' with data' => [
                PutMoneyOperation::class,
                [fn (): MoneyTaskData => MoneyTaskData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'])],
                Method::PUT,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
            ],
        ];
    }

    /**
     * @param class-string $class
     * @param array<string, mixed> $body
     * @param array<string, mixed> $sent
     */
    #[DataProvider('omittedFieldsProvider')]
    public function testReadOnlyAndOtherMethodFieldsAreLeftOutOfTheBody(string $class, array $body, array $sent): void
    {
        $this->connector()->send(new $class($body));

        $this->assertSame($sent, $this->mock->lastPendingRequest()?->body());
    }

    /**
     * @return array<string, array{class-string, array<string, mixed>, array<string, mixed>}>
     */
    public static function omittedFieldsProvider(): array
    {
        $customer = ['Name' => 'ACME', 'LastModifiedOn' => '2020-01-01', 'ChildCustomers' => [['ID' => 'c']], 'ProductPrices' => [['Price' => 1, 'ProductName' => 'Bread'], 'raw']];
        $customerSent = ['Name' => 'ACME', 'ProductPrices' => [['Price' => 1], 'raw']];
        $product = [
            'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1',
            'Type' => 'Stock',
            'SKU' => 'Bread',
            'AverageCost' => 1.5,
            'LastModifiedOn' => '2020-01-01',
            'BOMType' => 'None',
            'Suppliers' => [['SupplierName' => 'S', 'Currency' => 'USD']],
            'BillOfMaterialsProducts' => [['Name' => 'Flour', 'Quantity' => 1]],
            'CustomPrices' => [['Price' => 2, 'ProductName' => 'Bread']],
        ];
        $productSent = [
            'SKU' => 'Bread',
            'Suppliers' => [['SupplierName' => 'S']],
            'BillOfMaterialsProducts' => [['Quantity' => 1]],
            'CustomPrices' => [['Price' => 2]],
        ];

        return [
            PostCustomer::class => [PostCustomer::class, $customer, $customerSent],
            PutCustomer::class => [PutCustomer::class, $customer, $customerSent],
            PostProduct::class => [PostProduct::class, $product, ['Type' => 'Stock'] + $productSent],
            PutProduct::class => [PutProduct::class, $product, ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'] + $productSent],
            PostTax::class => [PostTax::class, ['Name' => 'VAT', 'TaxPercent' => 20], ['Name' => 'VAT']],
            PutTax::class => [PutTax::class, ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxPercent' => 20], ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1']],
            PostSaleOrder::class => [
                PostSaleOrder::class,
                ['SaleID' => 's', 'Lines' => [['SKU' => 'A', 'BackorderQuantity' => 2]]],
                ['SaleID' => 's', 'Lines' => [['SKU' => 'A']]],
            ],
            PostSalePayment::class => [
                PostSalePayment::class,
                ['ID' => 'p', 'TaskID' => 't', 'Type' => 'Payment', 'CreditID' => 'c', 'Amount' => 1],
                ['TaskID' => 't', 'Type' => 'Payment', 'Amount' => 1],
            ],
            PutSalePayment::class => [
                PutSalePayment::class,
                ['ID' => 'p', 'TaskID' => 't', 'Type' => 'Payment', 'CreditID' => 'c', 'Amount' => 1],
                ['ID' => 'p', 'CreditID' => 'c', 'Amount' => 1],
            ],
            'a non-list value is left alone' => [PostProduct::class, ['Suppliers' => 'raw'], ['Suppliers' => 'raw']],
        ];
    }

    public function testPutSaleLeavesThePostOnlySaleTypeOutOfTheBody(): void
    {
        $this->connector()->send(new PutSale(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SaleType' => 'Advanced']));
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'], $this->mock->lastPendingRequest()?->body());

        $this->connector()->send(new PutSale(SalePostPutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SaleType' => 'Advanced'])));
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'], $this->mock->lastPendingRequest()?->body());
    }

    public function testPostSaleKeepsTheSaleType(): void
    {
        $this->connector()->send(new PostSale(['Customer' => 'ACME', 'SaleType' => 'Simple']));
        $this->assertSame(['Customer' => 'ACME', 'SaleType' => 'Simple'], $this->mock->lastPendingRequest()?->body());
    }

    public function testPostProductLeavesTheIgnoredIdOutOfTheBody(): void
    {
        $this->connector()->send(new PostProduct(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread']));
        $this->assertSame(['SKU' => 'Bread'], $this->mock->lastPendingRequest()?->body());

        $this->connector()->send(new PostProduct(ProductData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread'])));
        $this->assertSame(['SKU' => 'Bread'], $this->mock->lastPendingRequest()?->body());
    }

    /**
     * @param array<string, mixed>|Closure(): ProductData $body
     */
    #[DataProvider('productPutWithoutIdProvider')]
    public function testPutProductNeedsTheId(array|Closure $body): void
    {
        $this->expectException(InvalidArgumentException::class);

        new PutProduct($body instanceof Closure ? $body() : $body);
    }

    /**
     * @return array<string, array{array<string, mixed>|Closure(): ProductData}>
     */
    public static function productPutWithoutIdProvider(): array
    {
        return [
            'array without ID' => [['Name' => 'Widget']],
            'array with empty ID' => [['ID' => '', 'Name' => 'Widget']],
            'array with null ID' => [['ID' => null]],
            'data without ID' => [fn (): ProductData => ProductData::from(['Name' => 'Widget'])],
        ];
    }

    public function testTheConcreteClassesAreExactlyTheProvidersClasses(): void
    {
        $this->assertSame(
            array_keys(array_filter(self::requestProvider(), static fn (string $key): bool => ! str_ends_with($key, ' with data'), ARRAY_FILTER_USE_KEY)),
            self::concreteRequestClasses(),
        );
    }

    public function testEachClassFolderMatchesItsResolvedEndpointPath(): void
    {
        foreach (self::concreteRequestClasses() as $class) {
            $request = new ReflectionClass($class)->newInstanceWithoutConstructor();
            $relativeNamespace = str_replace(['Ipsocode\Cin7\Requests\\', '\\' . new ReflectionClass($class)->getShortName()], '', $class);
            $folderAsPath = str_replace('\\', '/', $relativeNamespace);

            $this->assertSame(
                strtolower($request->resolveEndpoint()),
                strtolower($folderAsPath),
                $class,
            );
        }
    }

    public function testEachClassNameStartsWithItsVerb(): void
    {
        foreach (self::concreteRequestClasses() as $class) {
            $request = new ReflectionClass($class)->newInstanceWithoutConstructor();
            $shortName = new ReflectionClass($class)->getShortName();

            $this->assertStringStartsWith(ucfirst(strtolower($request->method()->value)), $shortName, $class);
        }
    }

    /**
     * Every non-abstract class under `src/Requests/`, sorted the way the provider lists them.
     *
     * @return list<class-string<Cin7Request>>
     */
    private static function concreteRequestClasses(): array
    {
        $root = __DIR__ . '/../../../src/Requests';
        $classes = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative = str_replace($root . '/', '', $file->getPathname());
            $class = 'Ipsocode\Cin7\Requests\\' . str_replace(['/', '.php'], ['\\', ''], $relative);

            if (! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->isAbstract()) {
                continue;
            }

            $classes[] = $class;
        }

        sort($classes);

        return $classes;
    }
}
