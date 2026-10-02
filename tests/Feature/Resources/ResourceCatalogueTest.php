<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Cin7Connector;
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
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per resource method, asserting on the request it builds and the PendingRequest
 * it sends.
 *
 * @see docs/resources.md
 */
class ResourceCatalogueTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 20, MockResponse::make(Cin7Payloads::customerList())));
    }

    /**
     * @param callable(Cin7Connector): mixed $call
     * @param array<string, mixed> $query
     * @param null|array<string, mixed> $body
     */
    #[DataProvider('resourceProvider')]
    public function testTheResourceMethodBuildsItsRequest(
        callable $call,
        string $requestClass,
        Method $method,
        string $path,
        array $query,
        ?array $body,
    ): void {
        $call($this->connector());

        $pending = $this->mock->lastPendingRequest();

        $this->assertNotNull($pending);
        $this->assertInstanceOf($requestClass, $pending->request());
        $this->assertSame($method, $pending->method());
        $this->assertSame($path, $pending->uri()->getPath());
        $this->assertSame($query, $pending->queryParameters());
        $this->assertSame($body, $pending->body());
    }

    /**
     * @return array<string, array{callable(Cin7Connector): mixed, string, Method, string, array<string, mixed>, null|array<string, mixed>}>
     */
    public static function resourceProvider(): array
    {
        return [
            'customer get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->get(),
                GetCustomer::class,
                Method::GET,
                '/ExternalApi/v2/customer',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'customer paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->paginate()->current(),
                GetCustomer::class,
                Method::GET,
                '/ExternalApi/v2/customer',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'customer post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->post(['Name' => 'ACME']),
                PostCustomer::class,
                Method::POST,
                '/ExternalApi/v2/customer',
                [],
                ['Name' => 'ACME'],
            ],
            'customer post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->post(CustomerData::from(['Name' => 'ACME'])),
                PostCustomer::class,
                Method::POST,
                '/ExternalApi/v2/customer',
                [],
                ['Name' => 'ACME'],
            ],
            'customer put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->put(CustomerData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'])),
                PutCustomer::class,
                Method::PUT,
                '/ExternalApi/v2/customer',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'],
            ],
            'product post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->post(ProductData::from(['SKU' => 'Bread'])),
                PostProduct::class,
                Method::POST,
                '/ExternalApi/v2/product',
                [],
                ['SKU' => 'Bread'],
            ],
            'product put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->put(ProductData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread'])),
                PutProduct::class,
                Method::PUT,
                '/ExternalApi/v2/product',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread'],
            ],
            'customer put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME']),
                PutCustomer::class,
                Method::PUT,
                '/ExternalApi/v2/customer',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'],
            ],
            'product get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->get(),
                GetProduct::class,
                Method::GET,
                '/ExternalApi/v2/product',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'product paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->paginate()->current(),
                GetProduct::class,
                Method::GET,
                '/ExternalApi/v2/product',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'product post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->post(['Name' => 'Widget']),
                PostProduct::class,
                Method::POST,
                '/ExternalApi/v2/product',
                [],
                ['Name' => 'Widget'],
            ],
            'product put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget']),
                PutProduct::class,
                Method::PUT,
                '/ExternalApi/v2/product',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget'],
            ],
            'ref tax get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->get(),
                GetTax::class,
                Method::GET,
                '/ExternalApi/v2/ref/tax',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'ref tax paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->paginate()->current(),
                GetTax::class,
                Method::GET,
                '/ExternalApi/v2/ref/tax',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'ref tax post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(['Name' => 'VAT']),
                PostTax::class,
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            'ref tax put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT']),
                PutTax::class,
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
            ],
            'ref customer credits get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->credits()->get(['CustomerID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'ShowUsedCredits' => true]),
                GetCustomerCredits::class,
                Method::GET,
                '/ExternalApi/v2/ref/customer/credits',
                ['CustomerID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'ShowUsedCredits' => 'true', 'page' => 1, 'limit' => 100],
                null,
            ],
            'ref customer credits paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->credits()->paginate()->current(),
                GetCustomerCredits::class,
                Method::GET,
                '/ExternalApi/v2/ref/customer/credits',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'moneyOperation get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->get('b039f19e-66f8-4309-a4b1-abf928303c88'),
                GetMoneyOperation::class,
                Method::GET,
                '/ExternalApi/v2/moneyOperation',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
                null,
            ],
            'moneyOperation post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->post(['TaskType' => 'Receive Money']),
                PostMoneyOperation::class,
                Method::POST,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskType' => 'Receive Money'],
            ],
            'moneyOperation put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->put(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED']),
                PutMoneyOperation::class,
                Method::PUT,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
            ],
            'moneyOperation delete' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->delete('b039f19e-66f8-4309-a4b1-abf928303c88'),
                DeleteMoneyOperation::class,
                Method::DELETE,
                '/ExternalApi/v2/moneyOperation',
                ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'false'],
                null,
            ],
            'moneyOperation delete with void' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
                DeleteMoneyOperation::class,
                Method::DELETE,
                '/ExternalApi/v2/moneyOperation',
                ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
                null,
            ],
            'moneyOperation post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->post(MoneyTaskData::from(['TaskType' => 'Spend Money', 'Note' => null])),
                PostMoneyOperation::class,
                Method::POST,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskType' => 'Spend Money'],
            ],
            'moneyOperation put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->put(MoneyTaskData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'])),
                PutMoneyOperation::class,
                Method::PUT,
                '/ExternalApi/v2/moneyOperation',
                [],
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'],
            ],
            'sale get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->get('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ['CombineAdditionalCharges' => true]),
                GetSale::class,
                Method::GET,
                '/ExternalApi/v2/sale',
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CombineAdditionalCharges' => 'true'],
                null,
            ],
            'sale post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->post(['Customer' => 'ACME']),
                PostSale::class,
                Method::POST,
                '/ExternalApi/v2/sale',
                [],
                ['Customer' => 'ACME'],
            ],
            'sale put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush']),
                PutSale::class,
                Method::PUT,
                '/ExternalApi/v2/sale',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'],
            ],
            'sale delete' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->delete('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'),
                DeleteSale::class,
                Method::DELETE,
                '/ExternalApi/v2/sale',
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Void' => 'false'],
                null,
            ],
            'sale delete with void' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->delete('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', void: true),
                DeleteSale::class,
                Method::DELETE,
                '/ExternalApi/v2/sale',
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Void' => 'true'],
                null,
            ],
            'sale order get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->order()->get('916ab4c0-6ccb-4c93-873d-0603859050e4', ['IncludeProductInfo' => true]),
                GetSaleOrder::class,
                Method::GET,
                '/ExternalApi/v2/sale/order',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludeProductInfo' => 'true'],
                null,
            ],
            'sale order post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->order()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
                PostSaleOrder::class,
                Method::POST,
                '/ExternalApi/v2/sale/order',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            ],
            'sale order post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->order()->post(SaleOrderData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK'])),
                PostSaleOrder::class,
                Method::POST,
                '/ExternalApi/v2/sale/order',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK'],
            ],
            'sale invoice get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
                GetSaleInvoice::class,
                Method::GET,
                '/ExternalApi/v2/sale/invoice',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
                null,
            ],
            'sale invoice post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
                PostSaleInvoice::class,
                Method::POST,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            ],
            'sale invoice post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->post(SaleInvoicePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'Memo' => 'Rush', 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00'])),
                PostSaleInvoice::class,
                Method::POST,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00', 'Memo' => 'Rush'],
            ],
            'sale invoice put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->put(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88']),
                PutSaleInvoice::class,
                Method::PUT,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            ],
            'sale invoice put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->put(SaleInvoicePutData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'])),
                PutSaleInvoice::class,
                Method::PUT,
                '/ExternalApi/v2/sale/invoice',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            ],
            'sale invoice delete' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->delete('b039f19e-66f8-4309-a4b1-abf928303c88'),
                DeleteSaleInvoice::class,
                Method::DELETE,
                '/ExternalApi/v2/sale/invoice',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'false'],
                null,
            ],
            'sale invoice delete with void' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
                DeleteSaleInvoice::class,
                Method::DELETE,
                '/ExternalApi/v2/sale/invoice',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
                null,
            ],
            'sale creditNote get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->get('916ab4c0-6ccb-4c93-873d-0603859050e4', ['IncludePaymentInfo' => true]),
                GetSaleCreditNote::class,
                Method::GET,
                '/ExternalApi/v2/sale/creditnote',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludePaymentInfo' => 'true'],
                null,
            ],
            'sale creditNote post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
                PostSaleCreditNote::class,
                Method::POST,
                '/ExternalApi/v2/sale/creditnote',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            ],
            'sale creditNote post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->post(SaleCreditNotePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Memo' => 'Damaged', 'Status' => 'AUTHORISED', 'CreditNoteDate' => '2017-11-22T00:00:00'])),
                PostSaleCreditNote::class,
                Method::POST,
                '/ExternalApi/v2/sale/creditnote',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Status' => 'AUTHORISED', 'CreditNoteDate' => '2017-11-22T00:00:00', 'Memo' => 'Damaged'],
            ],
            'sale creditNote delete' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
                DeleteSaleCreditNote::class,
                Method::DELETE,
                '/ExternalApi/v2/sale/creditnote',
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
                null,
            ],
            'sale payment get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
                GetSalePayment::class,
                Method::GET,
                '/ExternalApi/v2/sale/payment',
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
                null,
            ],
            'sale payment post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5]),
                PostSalePayment::class,
                Method::POST,
                '/ExternalApi/v2/sale/payment',
                [],
                ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5],
            ],
            'sale payment post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->post(SalePaymentPostData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0])),
                PostSalePayment::class,
                Method::POST,
                '/ExternalApi/v2/sale/payment',
                [],
                ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0],
            ],
            'sale payment put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->put(['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5]),
                PutSalePayment::class,
                Method::PUT,
                '/ExternalApi/v2/sale/payment',
                [],
                ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
            ],
            'sale payment put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->put(SalePaymentPutData::from(['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5])),
                PutSalePayment::class,
                Method::PUT,
                '/ExternalApi/v2/sale/payment',
                [],
                ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
            ],
            'sale payment delete' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->delete('ee093a0c-d177-9728-1df5-628a61a939e4'),
                DeleteSalePayment::class,
                Method::DELETE,
                '/ExternalApi/v2/sale/payment',
                ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4'],
                null,
            ],
            'moneyTaskList get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyTaskList()->get(['Status' => 'COMPLETED']),
                GetMoneyTaskList::class,
                Method::GET,
                '/ExternalApi/v2/moneyTaskList',
                ['Status' => 'COMPLETED', 'page' => 1, 'limit' => 100],
                null,
            ],
            'moneyTaskList paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->moneyTaskList()->paginate()->current(),
                GetMoneyTaskList::class,
                Method::GET,
                '/ExternalApi/v2/moneyTaskList',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'saleList get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->saleList()->get(['Status' => 'ORDERED']),
                GetSaleList::class,
                Method::GET,
                '/ExternalApi/v2/saleList',
                ['Status' => 'ORDERED', 'page' => 1, 'limit' => 100],
                null,
            ],
            'saleList paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->saleList()->paginate()->current(),
                GetSaleList::class,
                Method::GET,
                '/ExternalApi/v2/saleList',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'sale post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->post(SalePostPutData::from(['Customer' => 'ACME'])),
                PostSale::class,
                Method::POST,
                '/ExternalApi/v2/sale',
                [],
                ['Customer' => 'ACME'],
            ],
            'sale put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->put(SalePostPutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'])),
                PutSale::class,
                Method::PUT,
                '/ExternalApi/v2/sale',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'],
            ],
            'ref tax post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(TaxData::from(['Name' => 'VAT'])),
                PostTax::class,
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            'ref tax put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(TaxData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'])),
                PutTax::class,
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
            ],
        ];
    }
}
